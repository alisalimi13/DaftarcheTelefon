<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Contact;
use App\Person;
use App\NotPerson;
use App\Number;
use App\Email;
use App\Address;
use App\Comment;
use App\Group;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DaftarcheTelefon extends Controller
{
    // For Recognizing Type Of The Request
    public function requestProcess(Request $request)
    {
        //$type = $_REQUEST['type'];
        $type = $request['type'];
        
        switch ($type)
        {
            case 1:
                return $this->sendContacts();
                break;
            case 2:
                return $this->sendGroups();
                break;
            case 3:
                $contactID = $request['contact_id'];
                return $this->sendContactDetail($contactID);
                break;
            case 4:
                $groupID = $request['group_id'];
                return $this->sendGroupDetail($groupID);
                break;
            case 5:
                $contactID = $request['contact_id'];
                return $this->doDeleteContact($contactID);
                break;
            case 6:
                $groupID = $request['group_id'];
                return $this->doDeleteGroup($groupID);
                break;
            case 7:
                $string = $request['str'];
                $field = $request['field'];
                return $this->search($string,$field);
                break;
            case 8:
                $contactsFile = $request->file('contactsFile');
                return $this->importJSON($contactsFile);
                break;
            case 9:
                return $this->saveContact($request);
                break;
            case 10:
                return $this->saveGroup($request);
                break;
        }

        return;
    }

    // For Sending Contacts List
    public function sendContacts()
    {
        $people = Person::all();
        $notPeople = NotPerson::all();
        $peopleArr = null;
        $notPeopleArr = null;

        $i = 0;
        foreach ($people as $person)
        {
            $peopleArr[$i][0] = $person->contact_id;
            $peopleArr[$i][1] = $person->firstName;
            $peopleArr[$i][2] = $person->lastName;
            $i = $i + 1;
        }

        $i = 0;
        foreach ($notPeople as $notPerson)
        {
            $notPeopleArr[$i][0] = $notPerson->contact_id;
            $notPeopleArr[$i][1] = $notPerson->type;
            $notPeopleArr[$i][2] = $notPerson->title;
            $i = $i + 1;
        }

        $contactsArr = [$peopleArr, $notPeopleArr];

        return json_encode($contactsArr);
    }

    // For Sending Groups List
    public function sendGroups()
    {
        $groups = Group::all();
        $groupsArr = null;

        $i = 0;
        foreach ($groups as $group)
        {
            $groupsArr[$i][0] = $group->id;
            $groupsArr[$i][1] = $group->title;
            $i = $i + 1;
        }

        return json_encode($groupsArr);
    }

    // For Sending Contact Detail
    public function sendContactDetail($contactID)
    {
        $contact = Contact::find($contactID);
        if ($contact)
        {
            $contact->detail;
            $contact->groups;
            $contact->numbers;
            $contact->emails;
            $contact->addresses;
            $contact->comments;
        }

        return $contact;
    }

    // For Sending Group Detail
    public function sendGroupDetail($groupID)
    {
        $group = Group::find($groupID);
        if ($group)
        {
            $group->contacts;
            foreach ($group->contacts as $contact)
            {
                $contact->detail;
            }
        }

        return $group;
    }

    public function doDeleteContact($contactID)
    {
            $this->deleteContact($contactID);
            return "true";
    }

    public function doDeleteGroup($groupID)
    {
            $this->deleteGroup($groupID);
            return "true";
    }

    // For Adding New Cantact To Database
    public function addContact($contactID, $isPerson, $photoName, $firstName, $lastName, $isMale, $type, 
        $title, $numbers, $emails, $addresses, $comments, $groups)
    {
        // $id:int, $isPerson:bool, $photoName:str
        // if isPerson initializing $firstName:str, $lastName:str, $isMale:bool
        // else initializing $type:str, $title:str
        // $numbers:arr2-str-str, $emails:arr-str, $addresses:arr2-str-str, 
        // $comments:arr-str, $groups:arr-str

        // add to contact table and getting contact id
        if ($contactID > 0)
            Contact::create(['id'=>$contactID, 'isPerson'=>$isPerson, 'photoName'=>$photoName]);
        else
            $contactID = Contact::create(['isPerson'=>$isPerson, 'photoName'=>$photoName])->id;


        if ($isPerson)
        {
            // add to people table
            Person::create(['contact_id'=>$contactID, 'firstName'=>$firstName, 
                'lastName'=>$lastName, 'isMale'=>$isMale]);
        }
        else
        {
            // add to not_people table
            NotPerson::create(['contact_id'=>$contactID, 'type'=>$type, 'title'=>$title ]);
        }

        // add to numbers table
        if ($numbers)
        {
            foreach ($numbers as $number)
            {
                Number::create(['contact_id'=>$contactID, 'type'=>$number[0], 'number'=>$number[1]]);
            }
        }

        // add to emails table
        if ($emails)
        {
            foreach ($emails as $email)
            {
                Email::create(['contact_id'=>$contactID, 'email'=>$email]);
            }
        }

        // add to addresses table
        if ($addresses)
        {
            foreach ($addresses as $address)
            {
                Address::create(['contact_id'=>$contactID, 'type'=>$address[0], 'address'=>$address[1]]);
            }
        }

        // add to comments table
        if ($comments)
        {
            foreach ($comments as $comment)
            {
                Comment::create(['contact_id'=>$contactID, 'comment'=>$comment]);
            }
        }

        // add to contact_group table
        if ($groups)
        {
            foreach ($groups as $group)
            {
                Contact::find($contactID)->groups()->save(Group::where(['id'=>$group])->get()[0]);
            }
        }

        return;
    }

    // For Deleting A Contact
    public function deleteContact ($contactID)
    {
        $contactIsPerson = Contact::find($contactID)->isPerson;
        // delete from numbers table
        Number::where(['contact_id'=>$contactID])->delete();
        // delete from emails table
        Email::where(['contact_id'=>$contactID])->delete();
        // delete from addresses table
        Address::where(['contact_id'=>$contactID])->delete();
        // delete from comments table
        Comment::where(['contact_id'=>$contactID])->delete();
        
        if ($contactIsPerson)
        {
            // delete from people table
            Person::where(['contact_id'=>$contactID])->delete();
        }
        else
        {
            // delete from not_people table
            NotPerson::where(['contact_id'=>$contactID])->delete();
        }

        // delete from contact_group table
        DB::delete('DELETE FROM contact_group WHERE contact_id=' . $contactID);

        // delete from contact table
        Contact::find($contactID)->delete();

        return;
    }

    // For Adding New Group
    public function addGroup($groupID, $title, $photoName, $contactIDs)
    {
        // $title:str, $photoName:str, $contactIDs:arr:int

        // adding to groups table and getting group
        if ($groupID > 0)
            $group = Group::create(['id'=>$groupID,'title'=>$title, 'photoName'=>$photoName]);
        else
            $group = Group::create(['title'=>$title, 'photoName'=>$photoName]);

        // adding to contact_group table
        if ($contactIDs)
        {
            foreach ($contactIDs as $contactID)
            {
                $group->contacts()->save(Contact::find($contactID));
            }
        }

        return;
    }

    // For Deleting A Group
    public function deleteGroup($groupID)
    {
        // $groupID:int

        // deleting from contact_group table
        DB::delete('DELETE FROM contact_group WHERE group_id=' . $groupID);

        // deleting form groups table
        Group::find($groupID)->delete();

        return;
    }

    // For Searching
    public function search($str,$field)
    {
        
         if($field == 1)
        {
            $str = strtolower($str);
            $str = '%' .$str. '%' ;
            $hintName = array(); 
            $people = DB::Select('SELECT contact_id,firstName,lastName fROM people where firstName like ? OR lastName like ?', [$str,$str]);
            $notpeople = DB::Select('SELECT contact_id,type,title fROM not_people where title like ?',[$str]);
            $hintName = [$people,$notpeople] ;
            return json_encode($hintName);         
        }

        elseif($field == 2)
        {
            $str = '%' . $str.'%' ;
            $hintNum['people'] = array();
            $hintNum['notPeople'] = array();
          
            
            $results = DB::Select('SELECT contact_id,number From numbers Where number LIKE ? ',[$str]) ;
            foreach($results as $result)
            {
                $itemPeople = array() ;
                $itemNotPeople = array() ;
                $itemPeople['contact_id'] = $result->contact_id ;
                $itemPeople['number'] = $result->number ;
                $itemNotPeople['contact_id'] = $result->contact_id ;
                $itemNotPeople['number'] = $result->number ;
                $contact_id = $result->contact_id ;
                $peopleNum = DB::Select('SELECT * FROM people WHERE contact_id= ?',[$contact_id]) ;
                $notPeopleNum = DB::Select('SELECT * FROM not_people WHERE contact_id= ?',[$contact_id]) ;
                foreach($peopleNum as $e) {
                    $itemPeople['firstName'] = $e->firstName;
                    $itemPeople['lastName'] = $e->lastName;

                    array_push($hintNum['people'], $itemPeople) ;
                }
                foreach($notPeopleNum as $e) {
                    $itemNotPeople['title'] = $e->title;
                    $itemNotPeople['type'] = $e->type;

                    array_push($hintNum['notPeople'], $itemNotPeople) ;
                }
            }
  
            return json_encode($hintNum);    
        }
    }


    // For Save

    public function saveContact(Request $request)
    {
        if ($request['contactID'] > 0)
            $photoName = Contact::find($request['contactID'])->photoName;
        else
            $photoName = null;
        if ($request->file('photo'))
            $photoName = $request->file('photo')->store('ContactsPhotos');

        if ($request['contactID'] > 0)
        {
            $this->deleteContact($request['contactID']);
        }

        if ($request['isPerson'] == "true")
        {
            $this->addContact($request['contactID'], 1, $photoName, $request['firstName'], 
                $request['lastName'], ($request['isMale'] == "true"?1:0), null, null, $request['numbers'], $request['emails'], 
                $request['addresses'], $request['comments'], $request['groups']);
        }
        else
        {
            $this->addContact($request['contactID'], 0, $photoName, null, 
                null, null, $request['type-contact'], $request['title'], $request['numbers'], $request['emails'], 
                $request['addresses'], $request['comments'], $request['groups']);
        }

        return;
    }

    public function saveGroup(Request $request)
    {
        if ( $request['groupID'] > 0 )
            $photoName = Group::find($request['groupID'])->photoName;
        else
            $photoName = null;
        if ($request->file('photo'))
            $photoName = $request->file('photo')->store('GroupsPhotos');

        if ($request['groupID'] > 0)
        {
            $this->deleteGroup($request['groupID']);
        }

        $this->addGroup($request['groupID'], $request['title'], $photoName, $request['contacts']);

        return;
    }

    // For Get Photo
    public function getContactPhoto($id)
    {
        if ($id == -1)
            $photoName = "ContactsPhotos/DefaultMale.jpg";
        else
        {
            $photoName = Contact::find($id)->photoName;
            if (!$photoName)
            {
                if (Contact::find($id)->isPerson)
                {
                    if (Contact::find($id)->detail->isMale)
                        $photoName = "ContactsPhotos/DefaultMale.jpg";
                    else
                        $photoName = "ContactsPhotos/DefaultFemale.jpg";
                }
                else
                    $photoName = "ContactsPhotos/DefaultNotPerson.jpg";
            }
        }
        $storagePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
        $name = $storagePath . $photoName;
        $fp = fopen($name, 'rb');
        header("Content-Type: image/png");
        header("Content-Length: " . filesize($name));
        fpassthru($fp);
        exit;
    }

    public function getGroupPhoto($id)
    {
        if ($id == -1)
            $photoName = "GroupsPhotos/DefaultGroup.jpg";
        else
        {
            $photoName = Group::find($id)->photoName;
            if (!$photoName)
            {
                $photoName = "GroupsPhotos/DefaultGroup.jpg";
            }
        }
        $storagePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
        $name = $storagePath . $photoName;
        $fp = fopen($name, 'rb');
        header("Content-Type: image/png");
        header("Content-Length: " . filesize($name));
        fpassthru($fp);
        exit;
    }

    // Amin
    public function exportCSV()
    {
        $content = "cType,FisrtName,LastName,Gender,Type,Title,Numbers,Emails,Addresses,Comment\n";
        $contacts = Contact::all() ;
        

        foreach ($contacts as $contact)
        {
            if ($contact->isPerson)
            {
                $content .= "Person,";
                $content .= $contact->detail->firstName . ",";
                $content .= $contact->detail->lastName . ",";
                if($contact->detail->isMale == 1)
                {
                    $content .= "male,";
                }
                else
                {
                     $content .= "female,";
                }
                $content .= "-,";
                $content .= "-,";

            }
            else
            {
                $content .= "Not Person,";
                $content .= "-,";
                $content .= "-,";
                $content .= "-,";
                $content .= $contact->detail->type . ",";
                $content .= $contact->detail->title . ",";
            }

            $numbers = $contact->numbers ;
            $content .= '"';
            foreach ($numbers as $number)
            {
                $content .= $number->number . " , ";
            }
            $content .= '",';

            $emails = $contact->emails ;
            $content .= '"';
            foreach ($emails as $email)
            {
                $content .= $email->email . " , ";
            }
            $content .= '",';

            $addresses = $contact->addresses ;
            $content .= '"';
            foreach ($addresses as $address)
            {
                $content .= $address->address . " , ";
            }
            $content .= '",';

            $comments = $contact->comments ;
            $content .= '"';
            foreach ($comments as $comment)
            {
                $content .= $comment->comment . " , ";
            }
            $content .= '",';

            $content .= "\n";
        }

        return response($content)->header('Content-Type', 'text/csv');

    }

    public function exportJSON()
    {
        $contacts = Contact::all() ;
        foreach($contacts as $contact)
        {
            $contact->detail;
            $contact->numbers;
            $contact->emails;
            $contact->addresses;
            $contact->comments;
        }

        return response($contacts)->header('Content-Type', 'application/octet-stream') ;
    }

    public function importJSON($contactsfile)
    {
        $contacts = json_decode(file_get_contents($contactsfile), TRUE);
        
        $groups = [] ;
        foreach($contacts as $contact)
        {
            $isPerson = $contact['isPerson'] ;
            $photoName = $contact['photoName'] ;
            if($isPerson)
            {
                $firstName = $contact['detail']['firstName'] ;
                $lastName = $contact['detail']['lastName'] ;
                $isMale = $contact['detail']['isMale'] ;
                $type= null ;
                $title= null ;
            }
            else
            {
                $type = $contact['detail']['type'] ;
                $title = $contact['detail']['title'] ;
                $firstName = null ;
                $lastName  = null ;
                $isMale    = null ;
            }
            
            $numbers = [];
            $nums = $contact['numbers'] ;
            $i = 0 ;
            foreach($nums as $num)
            {
                $numbers[$i][0] = $num['type'] ;
                $numbers[$i][1] = $num['number'] ;
                $i = $i + 1 ;
            }

            $emails = [];
            $es = $contact['emails'] ;
            $i = 0 ;
            foreach($es as $e)
            {
                $emails[$i] = $e['email'] ;
                $i = $i + 1 ;
            }

            $addresses = [];
            $adds = $contact['addresses'] ;
            $i = 0 ;
            foreach($adds as $add)
            {
                $addresses[$i][0] = $add['type'] ;
                $addresses[$i][1] = $add['address'] ;
                $i = $i + 1 ;
            }

            $comments = [];
            $comms = $contact['comments'] ;
            $i = 0 ;
            foreach($comms as $comm)
            {
                $comments[$i] = $comm['comment'] ;
                $i = $i + 1 ;
            }
            
                
            $this->addContact(-1, $isPerson, $photoName, $firstName, $lastName, $isMale, $type, 
            $title, $numbers, $emails, $addresses, $comments, $groups) ;
        }
        
        return "OK";
    }


    // Fill Database With Some Value
    public function fill()
    {
        // add groups
        $this->addGroup(1, 'Friends', null, null);
        $this->addGroup(2, 'Co-Workers', null, null);
        $this->addGroup(3, 'Spanners', null, null);

        //add contacts
        $this->addContact(1, 1, null, "Ali", "Salimi", 1, null, 
            null, [['Home', '0216554'],['Phone', '0939']], ['alisalimi@live.com'], 
            [['Home', 'Andishe New Town'],['Work', 'Valiasre Square']], ['Kablayi', 'Daneshjoo'], [1, 2]);
        $this->addContact(2, 1, null, "Amin", "Izadi", 1, null, 
            null, [['Home', '0212121'],['Phone', '0935']], ['amin@google.com'], 
            [['Home', 'Tehran - Piruzi'],['Work', 'Valiasre Square']], ['Daneshjoo'], [1, 2]);
        $this->addContact(3, 1, null, "Ali", "Sara", 1, null, 
            null, null, null, null, null, null, [1]);
        $this->addContact(4, 1, null, "Javad", "Saeidi", 1, null, 
            null, null, null, null, null, null, [3]);
        $this->addContact(5, 1, null, "Zahra", "Karimi", 0, null, 
            null, [['Home', '021'],['Phone', '0912']], null, 
            [['Home', 'Andishe Phaze 3'],['Work', 'Qom']], null, null);
        $this->addContact(6, 0, null, null, null, null, 'Film Company', 
            'Truth', [['Fax', '0211313']], ['truth@live.com'], 
            [['Official Building', 'Andishe Phase 2']], ['Search For Truth'], [1, 2]);
        $this->addContact(7, 0, null, null, null, null, 'IT Company', 
            'Reality', [['Fax', '0411313']], ['reality@live.com'], 
            [['Official Building', 'Tabriz - Abureyhan']], ['Search For Reality'], [1, 2]);
    }
}