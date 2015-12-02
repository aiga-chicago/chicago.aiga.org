AIGA Chicago (Wordpress)
==========

## Get Started

* Download and Install [VirtualBox][1]
* Download and Install [Vagrant][2]
* Run ``` vagrant up ```
* Access Your Project at  [http://192.168.33.91/][3]

## Basic Vagrant Commands

### Start or resume your server
```bash
vagrant up
```

### Pause your server
```bash
vagrant suspend
```

### Delete your server
```bash
vagrant destroy
```

### SSH into your server
```bash
vagrant ssh
```

## Database Access

### MySQL

- Hostname: localhost or 127.0.0.1
- Username: root
- Password: root
- Database: scotchbox


## Setting a Hostname

If you're like me, you prefer to develop at a domain name versus an IP address. If you want to get rid of the some-what ugly IP address, just add a record like the following example to your computer's host file.

```bash
192.168.33.10 whatever-i-want.local
```

Or if you want "www" to work as well, do:

```bash
192.168.33.10 whatever-i-want.local www.whatever-i-want.local
```

 [1]: https://www.virtualbox.org/wiki/Downloads
 [2]: https://www.vagrantup.com/downloads.html
 [3]: http://192.168.33.91/