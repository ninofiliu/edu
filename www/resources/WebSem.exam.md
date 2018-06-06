# WebSem - exams cheat sheet

Frequent Q&As based on [the previous exams](https://drive.google.com/drive/u/0/folders/0B-zqdELcp6-BcXdiWEFTVkFVcVU).



### 2013


#### What was the original research paper published in 1945 that describes the hypertext concept? Give the author and the title of this article.

Author: Vannevar Bush
Paper: "As we may think", describes the Memex, a device in which people would store all their books, records and communications, mechanized so that it may be consulted with exceeding speed and flexibility.


#### When did Time Berners Lee propose the World Wide Web project?

1989


#### What was the first graphical Web Browser?

Wysiwyg (1990)


#### Give your own definition of the Semantic Web.

A proposed development of the World Wide Web in which data in web pages is structured and tagged in such a way that it can be read directly by computers. Pushed by the W3C, promotes common data formats and exchange protocols on the web, most fundamentally the RDF.


#### Give 3 specifications recommended by the W3C as part of the Semantic Web stack.

Some W3C recommendations:

* HTML 3.2 (1997)
* XML 1.0 and 1.1 (2006)
* Namespaces in XML so as to avoid names collisions (2006)
* SPARQL (2008)
* JSON-LD (2014)
* RDF XML syntax (2014)
* RDF N-Triples (2014)
* RDF Turtle (2014)
* RDF schema (2014)

#### Consider the following snippet and draw the RDF graph corresponding to this description using the standard RDF graph notation (rectangles for the literals and ellipses for resources). Write a human readable explanation of what does it mean.

```xml
<rdf:RDF
  xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
  xmlns:dc="http://purl.org/dc/elements/1.1/"
  xmlns:foaf="http://xmlns.com/foaf/0.1/"
>
  <rdf:Description rdf:about="http://www.flickr.com/photos/erroba/3007698875/">
    <dc:author rdf:resource="http://www.flickr.com/people/erroba/">
      <foaf:homepage rdf:resource="http://www.erroba.be/">
    </dc:author>
    <dc:title>Eiffel tower, Paris, France :: Fisheye :: HDR</dc:title>
    <foaf:depicts rdf:resource="http://dbpedia.org/resource/Eiffel_Tower"/>
  </rdf:Description>
</rdf:RDF>
```

```
(http://www.flickr.com/photos/erroba/3007698875/) ---|dc:author|------->(http://www.flickr.com/people/erroba/)
(http://www.flickr.com/photos/erroba/3007698875/) ---|dc:title|-------->[Eiffel tower, Paris, France :: Fisheye :: HDR]
(http://www.flickr.com/photos/erroba/3007698875/) ---|foaf:depicts|---->(http://dbpedia.org/resource/Eiffel_Tower)
(http://www.flickr.com/people/erroba/) --------------|foaf:homepage|---> (http://www.erroba.be/)
```

`http://www.flickr.com/photos/erroba/3007698875/` is the URI of a resource (here, a flickr post). The RDF precises what the resource depicts, what's its title, and who created it. The RDF precises the homepage of this author.


#### Consider now the knowledge represented in the snippet below. Explain what the machine will be able to infer regarding the first description. Draw the resulting RDF graphs displaying these inferences.

```xml
<rdf:RDF
  xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
  xmlns:dc="http://purl.org/dc/elements/1.1/"
  xmlns:foaf="http://xmlns.com/foaf/0.1/"
>
  <rdf:Property rdf:about="http://purl.org/dc/elements/1.1/author">
    <rdfs:subPropertyOf rdf:resource="http://purl.org/dc/elements/1.1/creator"/>
  </rdf:Property>
  <rdf:Description rdf:about="http://dbpedia.org/resource/Eiffel_Tower">
    <rdf:type rdf:resource="http://dbpedia.org/class/yago/SkyscrapersInParis"/>
    <foaf:name>La Tour Eiffel</foaf:name>
    <dbpedia:architect rdf:resource="http://dbpedia.org/resource/Stephen_Sauvestre">
  </rdf:Description>
</rdf:RDF>
```

A lot of things can be infered from this additional knowledge, here are some assertions that can be infered

* Erroba is the creator of the picture of the eiffel tower
* This picture depicts a skyscraper in Paris named "La Tour Eiffel"
* Stephen Sauvestre is the architect of a parisian skyscraper shot by Erroba

