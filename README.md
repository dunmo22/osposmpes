<p align="center"><img src="https://raw.githubusercontent.com/opensourcepos/opensourcepos/master/branding/emblem.svg" alt="Open Source Point of Sale Logo" width="auto" height="200"></p>
<h3 align="center">Open Source Point of Sale</h3>

<p align="center">
  <a href="#-introduction">Introduction</a> · <a href="#-live-demo">Demo</a> · <a href="#-installation">Installation</a> · 
  <a href="#-contributing">Contributing</a> · <a href="#-reporting-bugs">Bugs</a> · <a href="#-faq">FAQ</a> · 
  <a href="#-keep-the-machine-running">Donate</a> · <a href="#-license">License</a> · <a href="#-credits">Credits</a>
</p>

<p align="center">
<a href="https://app.travis-ci.com/opensourcepos/opensourcepos" target="_blank"><img src="https://api.travis-ci.com/opensourcepos/opensourcepos.svg?branch=master" alt="Build Status"></a>
<a href="https://app.gitter.im/#/room/#opensourcepos_Lobby:gitter.im?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge" target="_blank"><img src="https://badges.gitter.im/jekkos/opensourcepos.svg" alt="Join the chat at https://app.gitter.im"></a>
<a href="https://badge.fury.io/gh/opensourcepos%2Fopensourcepos" target="_blank"><img src="https://badge.fury.io/gh/opensourcepos%2Fopensourcepos.svg" alt="Project Version"></a>
<a href="https://translate.opensourcepos.org/engage/opensourcepos/?utm_source=widget" target="_blank"><img src="https://translate.opensourcepos.org/widgets/opensourcepos/-/svg-badge.svg" alt="Translation Status"></a>
</p>

## 👋 Introduction

Open Source Point of Sale is a web-based point of sale system. The application is written in PHP, uses MySQL (or MariaDB) as the data storage back-end, and has a simple but intuitive user interface.

The latest `3.4` version is a complete overhaul of the original software. It uses CodeIgniter 4 as a framework and is based on Bootstrap 3 using Bootswatch themes. Along with improved functionality and security.

The features include:

- Stock management (items and kits with an extensible list of attributes)
- VAT, GST, customer, and multi tiers taxation
- Sale register with transactions logging
- Quotation and invoicing
- Expenses logging
- Cash up function
- Printing and emailing of receipts, invoices and quotations
- Barcode generation and printing
- Database of customers and suppliers
- Multiuser with permission control
- Reporting on sales, orders, expenses, inventory status and more
- Receivings
- Gift cards
- Rewards
- Restaurant tables
- Messaging (SMS)
- Multilanguage
- Selectable Bootstrap based UI theme with Bootswatch
- MailChimp integration
- Optional Google reCAPTCHA to protect the login page from brute force attacks
- GDPR ready

## 🧪 Live Demo

We've got a live version of our latest master running for you to play around with and test everything out. It's a containerized install that will reinitialize when new functionality is merged into our code repository.

You can [find the demo here](https://demo.opensourcepos.org/) and log in with these credentials.  
👤 Username `admin`  
🔒 Password `pointofsale`

If you bump into an issue, please check [the status page here](https://status.opensourcepos.org/) to confirm if the server is up and running.

## 🖥️ Development Demo

Besides the demo of the latest master, we also have a development server that builds when there's a new commit to our repository. It's mainly used for testing out new code before merging it into the master. [It can be found here](https://dev.opensourcepos.org/).

The log in credentials are the same as the regular live demo.

## 💾 Installation

Please **refrain from creating issues** about installation problems before having read the FAQ and going through existing GitHub issues. We have a build pipeline that checks the sanity of our latest repository commit, and in case the application itself is broken then our build will be as well.

This application can be set up in _many_ different ways and we only support the ones described in [the INSTALL.md file](INSTALL.md).

For more information and recommendations on support hardware, like receipt printers and barcode scanners, read [this page](https://github.com/opensourcepos/opensourcepos/wiki/Supported-hardware-datasheet) on our wiki.

## ✨ Contributing

Everyone is more than welcome to help us improve this project. If you think you've got something to help us go forward, feel free to open a [pull request]() or join the conversation on [Element](https://app.gitter.im/#/room/#opensourcepos_Lobby:gitter.im).

Want to help translate Open Source Point of Sale in your language? You can find [our Weblate here](https://translate.opensourcepos.org), sign up, and start translating. You can subscribe to different languages to receive a notification once a new string is added or needs updating. Have a look at our [guidelines](https://github.com/opensourcepos/opensourcepos/wiki/Adding-translations) below to help you get started.

Only with the help of the community, we can keep language translations up to date. Thanks!

## 🐛 Reporting Bugs

Before creating a new issue, you'll need copy and include the info under the `System Info` tab in the configuration section in most cases. If that information is not provided in full, your issue might be tagged as pending.

If you're reporting a potential security issue, please refer to our security policy found in the [SECURITY.md](SECURITY.md) file.

NOTE: If you're running non-release code, please make sure you always run the latest database upgrade script and download the latest master code.

## 📖 FAQ

- If you get the message `system folder missing`, then you have cloned the source using git and you need to run a build first. Check [INSTALL.md](INSTALL.md) for instructions or download latest zip file from [GitHub releases](https://github.com/opensourcepos/opensourcepos/releases) instead.

- If at login time you read `The installation is not correct, check your php.ini file.`, please check the error_log in `public` folder to understand what's wrong and make sure you read the [INSTALL.md](INSTALL.md). To know how to enable `error_log`, please read the comment in [issue #1770](https://github.com/opensourcepos/opensourcepos/issues/1770#issuecomment-355177943).

- If you installed your OSPOS under a web server subdir, please edit `public/.htaccess` and go to the lines with the comments `if in web root` or `if in subdir`, uncomment one and replace `<OSPOS path>` with your path, and follow the instruction on the second comment line. If you face more issues, please read [issue #920](https://github.com/opensourcepos/opensourcepos/issues/920) for more information.

- Apache server configurations are SysAdmin issues and not strictly related to OSPOS. Please make sure you can show a "Hello world" HTML page before pointing to OSPOS public directory. Make sure `.htaccess` is correctly configured.

- If the avatar pictures are not shown in items or at item save you get an error, please make sure your `public` and subdirs are assigned to the correct owner and the access permission is set to `750`.

- If you install OSPOS in Docker behind a proxy that performs `ssloffloading`, you can enable the URL generated to be HTTPS instead of HTTP, by activating the environment variable `FORCE_HTTPS = 1`.

- If you install OSPOS behind a proxy and OSPOS constantly drops your session, consider whitelisting the proxy IP address by setting `$config['proxy_ips'] = '<proxy ip>';` in the [main PHP config file](https://github.com/opensourcepos/opensourcepos/blob/master/application/config/config.php). In extreme instances, changing `$config['sess_match_ip'] = true;` to `FALSE` may also help.

- If you have suhosin installed and face an issue with CSRF, please make sure you read [issue #1492](https://github.com/opensourcepos/opensourcepos/issues/1492).

- PHP `≥ 8.1` is required to run this app.

## 🏃 Keep the Machine Running

If you like our project, please consider buying us a coffee through the button below so we can keep adding features.

[![Donate](https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=MUN6AEG7NY6H8)\
Or refer to the [FUNDING.yml](.github/FUNDING.yml) file.

If you choose to deploy OSPOS in the cloud, you can contribute to the project by using DigitalOcean and signing up through our referral link. You'll receive a [free $200, 60-day credit](https://m.do.co/c/ac38c262507b) if you run OSPOS in a DigitalOcean droplet through [our referral link](https://m.do.co/c/ac38c262507b).

## 📄 License

Open Source Point of Sale is licensed under MIT terms with an important addition:

The footer signature "© 2010 - _current year_ · opensourcepos.org · 3.x.x - _hash_" including the version, hash and link to our website MUST BE RETAINED, MUST BE VISIBLE IN EVERY PAGE and CANNOT BE MODIFIED.

Also worth noting:

_The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software._

For more details please read the [LICENSE](LICENSE) file.

It's important to understand that although you are free to use the application, the copyright has to stay and the license agreement applies in all cases. Therefore, any actions like:

- Removing LICENSE and/or any license files is prohibited
- Authoring the footer notice replacing it with your own or even worse claiming the copyright is absolutely prohibited
- Claiming full ownership of the code is prohibited

In short, you are free to use the application, but you cannot claim any property on it.

Any person or company found breaching the license agreement might find a bunch of monkeys at the door ready to destroy their servers.

## 🙏 Credits

| <div align="center">DigitalOcean</div>                                                                                                                                                                                                                                                    | <div align="center">JetBrains</div> | <div align="center">Travis CI</div> |
|-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------| --- | --- |
| <div align="center"><a href="https://www.digitalocean.com?utm_medium=opensource&utm_source=opensourcepos" target="_blank"><img src="https://opensource.nyc3.cdn.digitaloceanspaces.com/attribution/assets/SVG/DO_Logo_horizontal_blue.svg" alt="DigitalOcean Logo" height="50"></a></div> | <div align="center"><a href="https://www.jetbrains.com/idea/" target="_blank"><img src="https://github.com/opensourcepos/opensourcepos/assets/12870258/187f9bbe-4484-475c-9b58-5e5d5f931f09" alt="IntelliJ IDEA Logo" height="50"></a></div> | <div align="center"><a href="https://www.travis-ci.com/" target="_blank"><img src="https://github.com/opensourcepos/opensourcepos/assets/12870258/71cc2b44-83af-4510-a543-6358285f43c6" alt="Travis CI Logo" height="50"></a></div> |
| Many thanks to [Digital Ocean](https://www.digitalocean.com) for providing the project with hosting credits                                                                                                                                                                               | Many thanks to [JetBrains](https://www.jetbrains.com/) for providing a free license of [IntelliJ IDEA](https://www.jetbrains.com/idea/) to kindly support the development of OSPOS. | Many thanks to [Travis CI](https://www.travis-ci.com/) for providing a free continuous integration service for open source projects. |
=======
[![Download](https://api.bintray.com/packages/jekkos/opensourcepos/opensourcepos/images/download.svg?version=3.3.2) ](https://bintray.com/jekkos/opensourcepos/opensourcepos/3.3.2/link)
[![Build Status](https://travis-ci.org/opensourcepos/opensourcepos.svg?branch=master)](https://travis-ci.org/opensourcepos/opensourcepos)
[![Join the chat at https://gitter.im/opensourcepos](https://badges.gitter.im/jekkos/opensourcepos.svg)](https://gitter.im/opensourcepos?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)
[![devDependency Status](https://david-dm.org/jekkos/opensourcepos/dev-status.svg)](https://david-dm.org/jekkos/opensourcepos#info=dev)
[![GitHub version](https://badge.fury.io/gh/jekkos%2Fopensourcepos.svg)](https://badge.fury.io/gh/jekkos%2Fopensourcepos)
[![Translation status](http://weblate.jpeelaer.net/widgets/ospos/-/svg-badge.svg)](http://weblate.jpeelaer.net/engage/ospos/?utm_source=widget)


Introduction
------------

Open Source Point of Sale is a web based point of sale system.
The main features are:
* Stock management (Items and Kits with extensible list of Attributes)
* VAT, GST, customer and multi tiers taxation
* Sale register with transactions logging
* Quotation and invoicing
* Expenses logging
* Cashup
* Receipt and invoice printing and/or emailing
* Barcode generation and printing
* Suppliers and Customers database
* Multiuser with permission control
* Reporting on sales, orders, expenses, inventory status
* Receivings
* Giftcard
* Rewards
* Restaurant tables
* Messaging (SMS)
* Multilanguage
* Selectable Boostrap (Bootswatch) based UI theme
* Mailchimp integration
* reCAPTCHA to protect login page from brute force attacks
* GDPR ready

The software is written in PHP language, it uses MySQL (or MariaDB) as data storage back-end and has a simple but intuitive user interface.

The latest 3.x version is a complete overhaul of the original software.
It is now based on Bootstrap 3 using Bootswatch themes, and still uses CodeIgniter 3 as framework.
It also has improved functionality and security.

Deployed to a Cloud it's a SaaS (Software as a Service) solution.

DEMO
----

A demo version of the latest master version can be found on our [Demo server](https://demo.opensourcepos.org). This is a containerized install which will be reinitialized when new functionality is added to the code repository.

LOGIN using
* username: admin
* password: pointofsale


Installation
------------

Please **refrain from creating issues** about installation problems **before having read the FAQ and went through existing github issues**. We have a build pipeline that checks the sanity of our latest repository commit and in case the application itself is broken then our build will be as well.

This application **can be setup in many different ways** and we only **support the ones described in the INSTALL file linked below**.

Read the [INSTALL.md](https://github.com/opensourcepos/opensourcepos/blob/master/INSTALL.md) in our repository.


License
-------

Open Source Point of Sale is licensed under MIT terms with an important addition:

_The footer signature "You are using Open Source Point Of Sale" with version, 
hash and link to the original distribution of the code MUST BE RETAINED, 
MUST BE VISIBLE IN EVERY PAGE and CANNOT BE MODIFIED._

Also worth noting:

_The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software._

For more details please read the file [LICENSE](https://github.com/opensourcepos/opensourcepos/blob/master/LICENSE).

It's important to understand that althought you are free to use the software the copyright stays and the license agreement applies in all cases.
Therefore any actions like:

- Removing LICENSE and any license files is prohibited
- Authoring the footer notice replacing it with your own or even worse claiming the copyright is absolutely prohibited
- Claiming full ownership of the code is prohibited

In short you are free to use the software but you cannot claim any property on it.

Any person or company found breaching the license agreement will have a bunch of monkeys at the door ready to destroy their servers.


Keep the Machine Running
------------------------

If you like the project, and you are making money out of it in some form, then consider buying us a coffee so we can keep adding features.

[![Donate](https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=MUN6AEG7NY6H8)


Language Translations
---------------------

To help us with OSPOS translations please use [Weblate website here](http://translate.opensourcepos.org) and sign up. After registering you can subscribe to different languages and you will be notified once a new translation is added.

Please also read the [wiki page here](https://github.com/opensourcepos/opensourcepos/wiki/Adding-translations) to find our Translations Guideline.

Only with the help of the community we can keep language translations up to date.


Reporting Bugs
--------------

If you are taking a release candidate code please make sure you always run the latest database upgrade script and you took the latest code from master.
Please DO NOT post issues if you have not completed this step.

- Versions **≥ 3.3.0**:

Please **Copy** the info under **System Info tab in configuration section** in order to give us the required details.

- Versions **< 3.2.3**:

Bug reports must follow the below schema:

1. Ospos **version string with git commit hash** (see ospos footer)
2. OS name and version running your Web Server (e.g. CentOS 6.9, Ubuntu 16.4, Windows 10)
3. Web Server name and version (e.g. Apache 2.2, Apache 2.4, Nginx 1.12, Nginx 1.13)
4. Database name and version (e.g. MySQL 5.5, MySQL 5.6, MySQL 5.7, MariaDB 10.0, MariaDB 10.1, MariaDB 10.2, MariaDB 10.3)
5. PHP version (e.g. 5.6, 7.0, 7.1, 7.2, 7.3)
6. Language selected in OSPOS (e.g. English, Spanish)
7. Any configuration of OSPOS that you changed
8. Exact steps to reproduce the issue (test case)
9. Optionally some screenshots to illustrate each step

If above information is not provided in full, your issue will be tagged as pending.
If missing information is not provided within a week we will close your issue.


FAQ
---

* If you are seeing the message **system folder missing**, then you have cloned the source using git and you need to run a build *first*. Check [INSTALL.md](https://github.com/opensourcepos/opensourcepos/blob/master/INSTALL.md) for instructions or download latest zip file from [bintray](https://bintray.com/jekkos/opensourcepos/opensourcepos/view/files?sort=updated&order=desc#files) instead.

* If at login time you read "The installation is not correct, check your php.ini file.", please check the error_log in public folder to understand what's wrong and make sure you read the [INSTALL.md](https://github.com/opensourcepos/opensourcepos/blob/master/INSTALL.md). To know how to enable error_log, please read the comment in [issue 1770](https://github.com/opensourcepos/opensourcepos/issues/1770#issuecomment-355177943).

* If you installed your OSPOS under a web server subdir, please edit public/.htaccess and go to the lines with comment `if in web root` and `if in subdir comment above line, uncomment below one and replace <OSPOS path> with your path` and follow the instruction on the second comment line. If you face more issues please read [issue #920](https://github.com/opensourcepos/opensourcepos/issues/920) for more help.

* Apache server configurations are SysAdmin issues and not strictly related to OSPOS. Please make sure you first can show a "hello world" html page before pointing to OSPOS public directory. Make sure .htaccess is correctly configured.

* If the avatar pictures are not shown in Items or at Item save time you get an error, please make sure your public and subdirs are assigned to the correct owner and the access permission is set to 755.

* If you install ospos in docker behind a proxy that performs ssloffloading, you can enable the url generated to be https instead of http, by activating the environment variable FORCE_HTTPS = 1.

* If you have suhosin installed and face an issue with CSRF, please make sure you read [issue #1492](https://github.com/opensourcepos/opensourcepos/issues/1492).

* If you see the item edit dialog box empty starting with version 3.3.0, please disable `only_full_group_by` option from MySQL/MariaDB. See issue [#2538](https://github.com/opensourcepos/opensourcepos/issues/2538).

Credits
-------
|JetBrains|Travis CI|
|:-:|:-:|
|![IntelliJ IDEA](https://raw.githubusercontent.com/wiki/j-easy/easy-batch/images/logo/intellijidea-logo.png)|[Travis CI](https://travis-ci.com/images/logos/TravisCI-Full-Color.png)|
|Many thanks to [JetBrains](https://www.jetbrains.com/) for providing a free license of [IntelliJ IDEA](https://www.jetbrains.com/idea/) to kindly support the development of OSPOS|Many thanks to [Travis CI](https://travis-ci.org) for providing a free continuous integration service for open source projects.|
 mpesa/dependabot/npm_and_yarn/ajv-6.12.6
