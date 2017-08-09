# QuickView
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Space48/QuickView/badges/quality-score.png?b=master&s=1c33bae08d098e004c6c9d6f8670e93c7ab3658f)](https://scrutinizer-ci.com/g/Space48/QuickView/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/Space48/QuickView/badges/build.png?b=master&s=f3224510aefc1156501e5b68aa3ee0cc034f12d9)](https://scrutinizer-ci.com/g/Space48/QuickView/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/Space48/QuickView/badges/coverage.png?b=master&s=5f785c28611df0c1ae00fa01a0d16d92e2e33091)](https://scrutinizer-ci.com/g/Space48/QuickView/?branch=master)

A module to quick view products for Magento2

## Installation

**Manually** 

To install this module copy the code from this repo to `app/code/Space48/QuickView` folder of your Magento 2 instance, then you need to run php `bin/magento setup:upgrade`

**Via composer**:

From the terminal execute the following:

`composer config repositories.space48-quick-view vcs git@github.com:Space48/QuickView.git`

then

`composer require "space48/quickview:{release-version}"`

## How to use it
Once installed, go to the `Admin Panel -> Stores -> Configuration -> Space48 -> Quick View` and `enable` the extension.
