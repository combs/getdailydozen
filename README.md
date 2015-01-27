# Get Daily Dozen 

The [Daily Dozen](http://yourshot.nationalgeographic.com/daily-dozen/) is a weekday selection of fantastic images shared on [Your Shot](http://yourshot.nationalgeographic.com/). We like to feature the Daily Dozen wherever possible, but some systems do not yet automatically ingest the feed. 

This script is a bit of connective glue to retrieve the JPEGs and their metadata, so that you can produce the Daily Dozen in other Nat Geo outlets.

## Setup Instructions

Click '[Download ZIP](https://github.com/combs/getdailydozen/archive/master.zip)' at the right, and double-click the downloaded .zip file. Move the "Get Daily Dozen" application somewhere you like--Desktop, Applications, doesn't matter. You can trash the other file it downloads. 

Open the app and, when prompted, enter your API key.

(This is the "password" that lets you download files this way. You only have to enter this once.)

## How to Use

Open the app. Enter the date to download--it defaults to today's date--and choose the folder to save them to.

It will churn silently for a minute or so, and then open the Finder window and a caption doc. 

You can drag all of the JPEGs into AEM in one batch.

Captions and credits can be copy/pasted out of the doc, or if you are uploading them to a CMS that supports EXIF/IPTC embeds, they should come through.

## Technical Details

This application requires Mac OS X 10.9 or newer. It could work with older OS X revisions but I haven't tested it.

The Your Shot API key is stored in ~/.yourshot-api-key.

The main script is built in PHP and is called by a wrapper AppleScript application. 

Many thanks to [Matías](http://stackoverflow.com/users/702353/mat%C3%ADas) on Stack Overflow for an [inline PHP class for changing IPTC values stored in EXIF](http://stackoverflow.com/questions/5384962/writing-exif-data-in-php).
