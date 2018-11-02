Learning Project - RDF Trig Parser
=========================

This is an attempt to write a RDF Trig Parser for PHP using PHP Parle extension. My understanding of Context Free Grammar was very limited at the time. 
While writting this parser I noticed that I used a LALR(1)- instead of an LL(1) parser generator. 
As a consequence this parser is not able to decode and process blank node property list and collection RDF Terms. 
I'm currently looking for a LL(1) parser generator. This will be part of another learning project.
