fobia
=====

fobia/fobia

    "require": {
        "wp-cli/php-cli-tools": "~0.9.4"
        "tecnick.com/tcpdf": "6.*"
    }


Интересные библиотеки

    "require-dev": {
        "slimcontroller/slimcontroller": "0.4.1",
        "wp-cli/php-cli-tools": "~0.9.4",
        "tecnick.com/tcpdf": "6.*",
        "kriswallsmith/buzz": "*",
        "dropbox/dropbox-sdk": "1.1.*"
    },

[LOCAL]  Memory usage: 1.70MB (peak: 2.25MB), time: 0.5008
[REMOTE] Memory usage: 1.15MB (peak: 1.75MB), time: 0.0107


## LAST_COMMIT

Файл `expand_date` :

    #!/usr/bin/env ruby
    data = STDIN.read
    last_date = `git log --pretty=format:"%ad" -1`
    puts data.gsub('$Date$', '$Date: ' + last_date.to_s + '$')

Конфигурация git:

    $ git config filter.dater.smudge expand_date
    $ git config filter.dater.clean 'perl -pe "s/\\\$Date[^\\\$]*\\\$/\\\$Date\\\$/"'



[slim-auth](https://github.com/jeremykendall/slim-auth)



