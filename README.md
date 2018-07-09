[![Build Status](https://travis-ci.org/Mil0dV/co2ok-plugin-woocommerce.svg?branch=master)](https://travis-ci.org/Mil0dV/co2ok-plugin-woocommerce)

# WooCommerce Plugin for CO2ok

A WooCommerce plugin to integrate CO2ok

## Installation

Some prose detailing installation

### Gulp & scss

To power up the development process we decided to use gulp for our task
which is currently just converting SCSS to css and minify it.

How to start with gulp and SCSS

SCSS is something on it's own, it's quite close to how css but on steroids
You can more learn about scss [here](https://sass-lang.com/).

To get Gulp working you follow the following steps:

1. Have Node.js and NPM installed on your OS.
2. Open your Terminal
3. Navigate into the plugin directory.
4. run `npm install gulp -g` to install Gulp globally.
5. Run `npm install` in the root folder.
6. after all packages are installed run `gulp`
7. Gulp should be starting now and automatic converting SCSS to css every time a `.scss` file is changed

- if you run into problems/errors with step 4. or 5. on Windows, these might be possible solutions (still in the root directory):
1. run `npm install --global --production windows-build-tools`
2. run `npm install -g which` 
3. run `which node`
4. run `npm install node-gyp@latest` 

### .po & .mo files for languages

To get a .mo file you can use for languages, you need to convert a .po file to .mo,
go to the following website, upload your .po file and press convert:
https://po2mo.net/

## Contributing

 1. **Fork** the repo on GitHub
 2. **Clone** the project to your own machine
 3. **Commit** changes to your own branch
 4. **Push** your work back up to your fork
 5. Submit a **Pull request** so that we can review your changes

### Branches will have the following structure

  **{Fix/Feature}/{What-your-doing}**

### Commit messages

  Commit messages will need be written in present tense like this:

  ❌ "Fix error messages" 

  And not like this:

  ✅ "Fixed error messages"

## License

This Plugin is licensed under the GPL v2 or later.

> This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License, version 2, as published by the Free Software Foundation.

> This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

> You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA

A copy of the license is included in the root of the plugin’s directory. The file is named `LICENSE`.
