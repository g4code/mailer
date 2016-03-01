mailer
======

> mailer - [php](http://php.net) library

## Install
Via Composer

```sh
composer require g4/mailer
```

## Usage

```php
<?php 
// Create message
$message = new \G4\Mailer\Message(
    'receiver@example.com',
    'sender@example.com',
    'Email subject',
    'This is a <strong>html</strong> email part',
    'This is a text email part'
);

// Config data
$options = [
    'delivery' => "smtp",
    'adapter'  => "smtp",
    'params'   => [
        'host'              => "smtp.example.com",
        'port'              => "587",
        'connection_class'  => "plain",
        'connection_config' => [
            'ssl'      => "tls",
            'username' => "smtp_username",
            'password' => "smtp_password"
        ]
    ]
];

// Create mailer instance
$mailer = \G4\Mailer\Mailer::factory($options);

// send message
$mailer->send($this->message);

?>
```

## Development

### Install dependencies

    $ make install

### Run tests

    $ make test

## License

(The MIT License)
see LICENSE file for details...
