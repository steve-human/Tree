# Overview #

This library contains some useful methods in managing adjacency lists and nested set trees.

This library is still in beta. Most of the functionality works, but still needs improvement and benchmarking. You cannot modify your tree using this library. It's main usefulness at the moment is converting your trees from nested sets to adjacency lists and vice versa. 

## Requirements ##

* PHP 5.3.0 or greater

## Current Features ##
* Create POPO (plain old PHP objects) from your database trees (adjacency list or nested set)
* Convert your nested set to an adjacency list and vice-versa
* Tighten up a nested set that may be "loose".
* Easily add a level property to your nested set if you don't have one already.
* toArray and fromArray methods to easily add and retrieve your tree

## To Do List ##
* An integrity checker to check the integrity of your tree. Mainly useful for nested sets (ex: left and right domains have gone bad).
* Add toNestedArray method which will return the tree as a nested array instead of a flat array.
* Add, move, delete node functionality. Need to experiment with this a bit to optimize speed.
* Add some unit testing

## LICENSE ##
The MIT License (MIT)

Copyright (c) 2013 Vouga Labs

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
the Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
