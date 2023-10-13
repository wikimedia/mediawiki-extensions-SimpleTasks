# SimpleTasks

## Installation
Execute

    composer require hallowelt/simpletasks dev-REL1_35
within MediaWiki root or add `hallowelt/simpletasks` to the
`composer.json` file of your project

## Activation
Add

    wfLoadExtension( 'SimpleTasks' );
to your `LocalSettings.php` or the appropriate `settings.d/` file.

# Defining a task

Wikitext:

Assign `This is task description` to MyUser, with due date `2023-10-12`

`[] This is task description [[User:MyUser]] <datetime>2023-10-12</datetime>`

Visual Mode:

Type `[]` which will create a checkbox, type description, then `@` to assign the user and (optionally) type (space) `//` to set the due date
