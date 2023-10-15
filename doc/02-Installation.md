# Installation <a id="module-customdashboards-installation"></a>

## Requirements <a id="module-customdashboards-installation-requirements"></a>

* Icinga Web 2 (&gt;= 2.11.4)
* PHP (&gt;= 7.3)

## Installation from .tar.gz <a id="module-customdashboards-installation-manual"></a>

Download the latest version and extract it to a folder named `customdashboards`
in one of your Icinga Web 2 module path directories.

## Enable the newly installed module <a id="module-customdashboards-installation-enable"></a>

Enable the `customdashboards` module either on the CLI by running

```sh
icingacli module enable customdashboards
```

Or go to your Icinga Web 2 frontend, choose `Configuration` -&gt; `Modules`, chose the `customdashboards` module and `enable` it.

It might afterwards be necessary to refresh your web browser to be sure that
newly provided styling is loaded.

## Initialize the module <a id="module-customdashboards-installation-enable"></a>

Run the following Command to initialize the module with the default parameters.

```sh
icingacli customdashboards init
```
