# [Keyup][1] Mass Shipment Extension for Magento

Mass Shipment is a simple extension for Magento that provides a mass action
for shipping orders. This is not provided in Magento by default because specific
parameters like package tracking number have to be set differently for each
shipment. However there are edge cases where this is not necessary, and this
extensions aims to help in these cases.

## Usage

The extension has no configuration options and only provides two new mass
actions in the Orders grid (Sales → Orders). Those are:

* _Ship (no emails)_ – creates a shipment (containing all shippable order items)
  for every selected order
* _Ship (with emails)_ – creates shipments the same way _and_ sends shipment
  notification emails to customers

## Compatibility

This extension was tested with Magento CE 1.9, but no really version-specific
code is used, so it should work with both newer and older (at least back till
1.7) Magento versions, and most possibly it will work with Magento Enterprise
too.

## Contributors

* [Both Interact GmbH][100]
* [febrez][101]
* [Ray Foss][102]

## License

The code is licensed under the MIT license. We'll appreciate if you
[let us know][2] that (and how) you use this extension.

[1]: http://www.keyup.eu/?utm_source=github&utm_medium=static&utm_campaign=mass_shipment
[2]: http://www.keyup.eu/en/contact?utm_source=github&utm_medium=static&utm_campaign=mass_shipment
[100]: https://github.com/both-interact
[101]: https://github.com/febrez
[102]: https://github.com/Reyncor
