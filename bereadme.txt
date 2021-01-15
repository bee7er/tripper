# Set up database

    vagrant ssh
    mysql -uroot -psecret
    create database trip
    GRANT ALL ON trip.* TO trip_admin@'localhost' IDENTIFIED BY 'Cantata3546';

    php artisan migrate
    php artisan db:seed

# Kick off in a browser with:

    http://tripper.test/

# TODO

    What does a condition look like?

        if CTX1.field REL CTX2.field

            CTX1 = CON, LST, VAR

            CTX2 = CON, LST, VAR


    Add Constants to Lists

    After Add Constant to list then reload the list so we can see it

    Allow constants to be deleted from the list

    Maintain Constants separately too?

    Make it so that once an action has been created that its type cannot be changed.  In this way the editing of the
    action can be actioned correctly.

    Create the action and then edit it to complete the details.

    When selecting a snippet do not include the calling function

    Add response and required details to edit question

    Pass in the zoom list so we avoid recursion

    Maybe allow selection of the snippet list in the edit action modal

    If the session has timed out then capture that and return a message or something.  Currently looks awful.


