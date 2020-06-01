<?php

use App\Contact;
use App\Person;
use App\NotPerson;
use App\Number;
use App\Email;
use App\Address;
use App\Comment;
use App\Group;
use Illuminate\Support\Facades\DB;

requestProcess();

// For Recognizing Type Of The Request
function requestProcess()
{
    $type = $_REQUEST['type'];
    
    switch ($type)
    {
        case 1:
            return sendContacts();
            break;
        case 2:
            return sendGroups();
            break;
    }
    return;
}

// For Sending Contacts List
function sendContacts()
{
    $people = Person::all();
    $notPeople = NotPerson::all();
    $peopleArr[0][0] = 0;
    $notPeopleArr[0][0] = 0;

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
function getGroups()
{
    $groups = Group::all();
    $groupsArr[0][0] = 0;

    $i = 0;
    foreach ($groups as $group)
    {
        $groupsArr[$i][0] = $group->id;
        $groupsArr[$i][1] = $group->title;
        $i = $i + 1;
    }

    return json_encode($groupsArr);
}

// For Adding New Cantact To Database
function addContact($contactID, $isPerson, $photoName, $firstName, $lastName, $isMale, $type, 
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
        $contactID = Contact::create(['isPerson'=>$isPerson, 'photoName'=>$photoName])->get()->id;


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
    foreach ($numbers as $number)
    {
        Number::create(['contact_id'=>$contactID, 'type'=>$number[0], 'number'=>$number[1]]);
    }

    // add to emails table
    foreach ($emails as $email)
    {
        Email::create(['contact_id'=>$contactID, 'email'=>$email]);
    }

    // add to addresses table
    foreach ($addresses as $address)
    {
        Address::create(['contact_id'=>$contactID, 'type'=>$address[0], 'address'=>$address[1]]);
    }

    // add to comments table
    foreach ($comments as $comment)
    {
        Comment::create(['contact_id'=>$contactID, 'comment'=>$comment]);
    }

    // add to contact_group table
    foreach ($groups as $group)
    {
        Contact::find($contactID)->groups()->save(Group::where(['title'=>$group])->get());
    }

    return;
}

// For Deleting A Contact
function deleteContact ($contactID)
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
    
    if ($conatctIsPerson)
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
function addGroup($groupID, $title, $photoName, $contactIDs)
{
    // $title:str, $photoName:str, $contactIDs:arr:int

    // adding to groups table and getting group
    if ($groupID > 0)
        $group = Group::create(['id'=>$groupID,'title'=>$title, 'photoName'=>$photoName]);
    else
        $group = Group::create(['title'=>$title, 'photoName'=>$photoName]);

    // adding to contact_group table
    foreach ($contactIDs as $conatctID)
    {
        $group->contacts()->save(Contact::find($contactID));
    }

    return;
}

// For Deleting A Group
function deleteGroup($groupID)
{
    // $group:int

    // deleting from contact_group table
    DB::delete('DELETE FROM contact_group WHERE group_id=' . $groupID);

    // deleting form groups table
    Group::find($groupID)->delete();

    return;
}

