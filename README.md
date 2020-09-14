# Magento 2 script runner

Run scripts in the Magento 2 application.

**PLEASE DO NOT USE THIS FOR ANYTHING EXCEPT DEBUGGING, AND KEEP IT AWAY FROM PRODUCTION!**

## Usage
Put this directory anywhere under your Magento 2 install directory.

```shell script
$ php script.php --help

Usage: script.php [options] filename [area-code]

Options:
 -c, --create filename   Create a new script file
 -h, --help              Display this help text
```

You can then put files in the `scripts` directory, and execute them like this:

```shell script
$ php script.php hello.php
hello
```

To create a new file, simply do `php script.php --create my_script.php`.

## Installation
Clone this repository into your Magento folder. Remember not to commit it.

You can also do this, athough you shouldn't. It will install it under `./script_runner`.
```shell script
bash -c "$(curl -SsfL https://raw.githubusercontent.com/marcusirgens/magento2-script-runner/main/install.sh)"
```

If you're in your Magento 2 application directory at `/my/code/folder`,
and you install this using the one-liner above, you put your script files in 
`/my/code/folder/script_runner/scripts`, and you execute the script runner by
running `php ./script_runner/script.php`.

## Why..?
Because sometimes, writing a quick one-off script is the way to go, and it's
especially useful if you're trying to debug something. In fact, this was written
to help out with running xdebug.

## License
[MIT](LICENSE.txt).
