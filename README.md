RDF PHP library - (Learning project)
=========================

This is an attemp to write a Trig Parser for PHP using PHP Parle extension. My understanding of CFG was very limited at the time. While writting this parser I noticed that I used a LALR(1) scanner and parser generator instead of an LL(1) generator. This means that collections and blank node property lists are not properly supported. 
