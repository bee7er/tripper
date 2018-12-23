# Set up database

    php artisan migrate
    php artisan db:seed

# Kick off in a browser with:

    http://localhost/tripper/public/

# TODO

    Allow user to reselect the snippet id

    Introduce lightbox and get the whole form styling right

    Pass in the zoom list so we avoid recursion

    Show the snippet name on the action line

    Maybe allow selection of the snippet list in the edit action modl

    Edit question, too

    Edit other action types, too

    Position of the form

    Restyle the form

    If the session has timed out then capture that and return a message or something.  Currently looks awful.

    ==============

    Create instance sub-classes for all types

        isComplete - should return a list of messages for why incomplete

        other functions, put in here

            getInsertAction ; after, before and inside

