unreleased
- CO-2876 implement model count
-----

5.3.0
-----
- CO-2544 prevent zip bomb upload during image push
- CO-2177 add support for warning messages in response
- CO-2601 add missing factories
- CO-2761 update setter name

5.2.12
-----
- add provide for jtl/connector-mapping-tables and jtl/connector-dbc

5.2.9
-----
- add additional fallback in case ChunkedHandler cannot be loaded

5.2.8
-----
- remove fallback check, instead use pragmatic approach and assume monolog 1

5.2.7
-----
- catch OutOfBoundsException if composer got overloaded but without a monolog version

5.2.6
-----
- fix abstract method error if monolog 1 is used by third party (like prestashop modules)

5.2.5
-----
- CO-2509 add tracking URLs to delivery notes

5.2.4
-----
- add chunked log handler to prevent to big log messages

5.2.3
-----
- fix clear all linkings

5.2.2
-----
- fix log level filtering
- remove fingers crossed log handler

5.2.1
-----
- update min php version to 8.0

5.2.0
-----
- stricter type checking

5.1.4
-----
- CO-2251 prevent path leaks
- add option to set formatter for combined log

5.1.3
-----
- CO-2056 add fallback for unknown attribute types

5.1.2
-----
- CO-2035 Github PR-13

5.1.1
-----
- Fix PHP 8.1 implicit casting

5.1.0
-----
- Fix PHP 8.1 compatibility

5.0.6
-----
- Added Mollie payment type

5.0.5
-----
- Replaced vat with uuid

5.0.4
-----
- Updated product price model definition

5.0.3
-----
- Fixed sorting product price items

5.0.2
-----
- Revised identification string handling in some models

5.0.1
-----
- Switched logging exception stack trace to error level

5.0.0
-----
- Added config option serializer.enable_cache
- Added types to translatable attributes
- Moved connector dependency to run method
- Removed type classes
- Revised identification string handling in models
- Simplified translatable attributes handling

4.2.1
-----
- Added tif/tiff mime type mappings

4.2.0
-----
- Added id to cross-selling item model
- Added cross-selling group property to cross-selling item model
- Refactored cross-selling name in definition constants

4.1.8
-----
- Changed handleRequest() method visibility to public

4.1.7
-----
- Added logic for determining image file extension by mime type

4.1.6
-----
- Added tax rate factory

4.1.5
-----
- Fixed tax rate model namespace in annotation

4.1.4
-----
- Added ack events

4.1.3
-----
- Fixed core clear action

4.1.2
-----
- Added tax rates to product model
- Added country iso property to tax rate model

4.1.1
-----
- Revised SQLite session handler

4.1.0
-----
- Added tax class id to product
- Revised rpc event handling
- Moved product price and product stock transformation to rpc event 

4.0.12
------
- Added support for symfony 4 and older monolog versions

4.0.11
------
- Added category- and product- attributes for possibility of linking them

4.0.10
------
- Fixed manipulating image endpoint for use with connector client

4.0.9
-----
- Removed outdated properties and methods from abstract data model

4.0.8
-----
- Replaced faker dependency, due to missing compatibility with PHP 8

4.0.7
-----
- Added simple token validator

4.0.6
-----
- Fixed return value of identification strings method in specific model

4.0.5
-----
- Added identification string to specific model

4.0.4
-----
- Fixed sqlite session database existence check (thanks to @infastra)
- Added global overrides to generator factories
- Added generator factories for specific models

4.0.3
-----
- Added possibility to extend image identification strings during synchronisation

4.0.2
-----
- Added connector identification and init event

4.0.1
-----
- Added identification strings to image, delivery note and status change models

4.0.0
-----
- Added Action, Controller, Event, Model,... definitions
- Added clear specific mappings support
- Added config schema definition
- Added flexible logging service
- Added flexible session handler support
- Added usage of PHP-DI container
- Added usage of Symfony requests
- Added unit tests
- Refactored endpoint implementation
  - Revised connector interface
  - Added optional implementation of handle method by using HandleRequestInterface
- Refactored events  
- Refactored models
  - Unified attribute handling
  - Unified translations handling
  - Switched to language iso 639-1 (and 639-2 as fallback only)
- Removed unnecessary files
- Removed usage of singletons
- Renamed classes and interfaces to be conform with PSR naming conventions (https://www.php-fig.org/bylaws/psr-naming-conventions/)
- Replaced TokenLoader by TokenValidator
- Revised directory structure
- Revised plugin structure
- ...
- ...
- ...

3.1.20
-----
- Added ignoring properties with null values when deserializing a model

3.1.19
-----
- Added customer note property to customer order

3.1.18
-----
- Added discountable property to product

3.1.17
-----
- Fixed setting parameters in file handler

3.1.16
-----
- Added additional handling time property
- Added calculate handling time method

3.1.15
-----
- Added possibility to change log formatter
- Fixed compatibility to Symfony event-dispatcher 5 

3.1.14
-----
- Removed incompatible symfony/event-dispatcher version from composer

3.1.13
-----
- Removed stripslashes call

3.1.12
-----
- Removed log level and display errors calls in error handler
- Removed deprecated get_magic_quotes_gpc call
- Switched order in dispatch (symfony event) calls to prevent deprecated messages
- Updated composer dependencies to establish symfony 5 compatiblity
- Changed doctrine array collection to array

3.1.11
-----
- Added locale irish in Ireland

3.1.10
-----
- Removed infinity loop in session class to avoid warning

3.1.9
-----
- Delete old Linking before saving the new on 

3.1.8
-----
- Exception handling while encode or decode json revised.

3.1.5
-----
- Default logging channel switched to global
- Logging channel constants defined

3.1.4
-----
- Avoid warning in switch statement

3.1.0
-----
- CO-204 Connector linkings extended
- Skip unknown entity ack's
- Token loader is not needed in case a token validator is implemented
- ShippingClass identity mapping added to product

3.0.3
-----
- Cross selling events added

3.0.2
-----
- ShippingClass mappings added

3.0.1
-----
- Payment types added

3.0.0
-----
- CO-209 Added overwrite config and feature dependency
- CO-213 Changed php requirement to >= 7.1.3

2.4.12
------
- CO-200 Remove session.save_handler from php settings

2.4.11
------
- Added license data (LGPL-3.0-or-later)

2.4.10
------
- Added throwable and exception switching for php version compatibility
- Changed monolog/monolog dependency to 1.*

2.4.9
-----
- CO-122 Added new connector finish event
- CO-154 Added new CustomerOrderItem note property

2.4.8
-----
- Fixed minor syntax bug

2.4.7
-----
- Added customer note

2.4.6
-----
- Fixed connector server info annotation bug
- Changed vendor versions to < PHP 7 requirements

2.4.5
-----
- Added connector core debug and log call

2.4.4
-----
- Fixed minor Checksum Linker bug
- Fixed Serializer Annotation bug

2.4.3
-----
- Fixed exception error handler

2.4.2
-----
- Added manufacturer property to product model
- Changed exception message in error handler
- Fixed identity linker is type bug

2.4.1
-----
- Added compatibility method to config class
- Added sys_get_temp_dir fallback

2.4.0
-----
- Major config system refactoring

2.3.0
-----
- Casting endpoint id on identity model to string

2.2.25
------
- Changed some method comments
- Added Datamodel setIdentity method
- Identitylinker refactoring

2.2.24
------
- Changed language core utility to intercept empty strings

2.2.23
------
- Changed position of incoming request packet logging
- Fixed after statistic event parameters

2.2.22
------
- Added product property 'newReleaseDate'

2.2.21
------
- Added more locales in our language utility

2.2.20
------
- Changed session lifetime to session.gc_maxlifetime

2.2.19
------
- Downgrading Monolog to version 1.17.2

2.2.18
------
- Changed Monolog to version 1.19.x
- Added php 7 compatibility

2.2.17
------
- Added imagei18n to identity linker

2.2.16
------
- Fixed event property names

2.2.15
------
- Fixed imagei18n type classname

2.2.14
------
- Added property name to image
- Added entity ImageI18n
- Changed and fixed Event parameter

2.2.13
------
- Added 2 new customer order item types

2.2.12
------
- Added heidelpay payment type

2.2.11
------
- Added productId to delivernoteitem

2.2.10
------
- Added new payment types for skrill and paymorrow

2.2.9
-----
- Addded customer order and customer order item gross prices

2.2.8
-----
- Added token validator

2.2.7
-----
- Fixed php 7 error handling support

2.2.6
-----
- Added specific value type mapping to the identitylinker
- Added connector statistic event
- Added core connector features event
- Correct model namespaces in PHPDoc

2.2.5
-----
- Fixed ics zip file bug

2.2.4
-----
- Added billsafe to payment types

2.2.3
-----
- Added customer order pui property

2.2.2
-----
- Added paypal plus and amazon payments payment types

2.2.1
-----
- Improve HHVM compatibility by add @ReadOnly annotation to the core DataModel class

2.2.0
-----
- Introduce new constant LOG_DIR that controls the log directory used by Monolog

2.1.1
-----
- Added id to crossselling entity
- Added identity linker crossselling id mapping

2.1.0
-----
- Changed protocol version to 7 due to product image masterkill sorting
- Use stable version of doctrine/collections

2.0.1
-----
- Removed class controller helper
- Changed seo method noDiacritics to replaceDiacritics and makes it public
- Fixed logger force writing flag

2.0.0
-----
- Added initial config "developer_logging" flag
- Removed base connector get and set config

1.0.1
-----
- Changed monolog stream handler to rotate handler

1.0.0
-----
- Added property ean to product variation value
- Change jms serializer version to 1.0
