# Luchronia

This project have been developed between 2013 and 2015 as a web multiplayer
game. As we did not reached enough player, the project was declared "dead" and
the server were disconnected.

This github project is there to make it available for whoever find it
interresting. Feel free resurect it.

## Build and setup

Deploying the game on a lamp server takes two steps : deploying php scripts and
then, the database patches.

### Deploying php config

Go to the source php directory and uses the following command line. The `Dev`
directory can be change to point to wherever you've put the config files.

```bash
php -f build/build.php -- -web-config build/Dev/web-config.php -cmd-config build/Dev/cmd-config.php -crontab build/Dev/crontab.ini
```

This will copy the config files to relevant place (_i.e._ the web and
cmd directories) and install the cronjob (which will update the world).

### Deploying and updating SQL database

Before updating the SQL data, one may think of dumping the I18N tables. The
following line will dump the `fr` (french) translations and put it in an SQL
file under `SQL\i18n` directory.

```bash
php -f dumpI18N.php -- -code fr -config build/Dev/config.php
```

To deploy and update the database, run the following command. Again, replace the
`Dev` directory with your specific config.

```bash
php -f build/build.php -- -config build/Dev/config.php
```

This will update the database structure and content (or create everything the
first time).

### HTML Purifier

You need [htmlpurifier][1] to filter the user inputs. Here are how to install
it from `pear` :

```bash
sudo pear channel-discover htmlpurifier.org
sudo pear install hp/HTMLPurifier
```

You may need to set writeable mod in the html purifier directory

```bash
sudo chmod -R 0777 /usr/share/php/HTMLPurifier/DefinitionCache/Serializer
```

[1]: http://htmlpurifier.org/download

## Licence

### Code and application

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the “Software”), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
the Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

The Software is provided “as is”, without warranty of any kind, express or
implied, including but not limited to the warranties of merchantability, fitness
for a particular purpose and noninfringement. In no event shall the authors or
copyright holders X be liable for any claim, damages or other liability, whether
in an action of contract, tort or otherwise, arising from, out of or in
connection with the software or the use or other dealings in the Software.

Except as contained in this notice, the name of the authors shall not be used
in advertising or otherwise to promote the sale, use or other dealings in this
Software without prior written authorization from the authors.

### Images

All images and artistic files are relased under the terms of the Creative Common
licence as found here : https://creativecommons.org/licenses/by/4.0/legalcode.fr
