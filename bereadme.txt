# Set up database

    php artisan migrate
    php artisan db:seed

# Kick off in a browser with:

    http://localhost/tripper/public/

# TODO

    Add Constants to Lists

    Maintain Constants separately too?

    Make it so that once an action has been created that its type cannot be changed.  In this way the editing of the
    action can be actioned correctly.

    Create the action and then edit it to complete the details.

    When selecting a snippet do not include the calling function

    Add lists to the Question type list

    Add response and required details to edit question

    Edit question:

        The text for the question is just a comment in the action diagram

        Response:

            free-format - text
            currency - 10,2
            percentage - 0 to 100

            list - of discrete conditions

        Have a response type and subclass that

    Pass in the zoom list so we avoid recursion

    Maybe allow selection of the snippet list in the edit action modal

    If the session has timed out then capture that and return a message or something.  Currently looks awful.


