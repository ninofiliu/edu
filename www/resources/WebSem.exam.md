# WebSem - exams cheat sheet

Frequent Q&As based on [the previous exams](https://drive.google.com/drive/u/0/folders/0B-zqdELcp6-BcXdiWEFTVkFVcVU).


#### What was the original research paper published in 1945 that describes the hypertext concept? Give the author and the title of this article. (2013, 2014, 2015, 2016)

*alternative formulations: What is the Memex and why was it influential for the Web? What did Vanevar Bush invent and was it influential for the Web?*

Author: Vannevar Bush

Paper: "As we may think", describes the Memex, a device in which people would store all their books, records and communications, mechanized so that it may be consulted with exceeding speed and flexibility.

This is actually very close to the idea of hypertext and hyperlinks that the web is based on.


#### When did Time Berners Lee propose the World Wide Web project? (2013, 2014, 2015, 2016)

1989


#### What was the first graphical Web Browser? (2013, 2014, 2015)

Wysiwyg (1990)


#### Give your own definition of the Semantic Web. (2013, 2014, 2015, 2016)

A proposed development of the World Wide Web in which data in web pages is structured and tagged in such a way that it can be read directly by computers. Pushed by the W3C, promotes common data formats and exchange protocols on the web, most fundamentally the RDF.


#### Give 3 specifications recommended by the W3C as part of the Semantic Web stack. (2013, 2014, 2015, 2016)

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

#### Consider the following snippet and draw the RDF graph corresponding to this description using the standard RDF graph notation (rectangles for the literals and ellipses for resources). Write a human readable explanation of what does it mean. (2013, 2014, 2015)

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

JSON representation of the graph:

```
{
  "nodes": [
    "(http://www.flickr.com/photos/erroba/3007698875/)",
    "(http://www.flickr.com/photos/erroba/)",
    "[Eiffel tower, Paris, France :: Fisheye :: HDR]",
    "(http://dbpedia.org/resource/Eiffel_Tower)",
    "(http://www.erroba.be/)"
  ],
  "edges": [
    {
      "object": "(http://www.flickr.com/photos/erroba/3007698875/)",
      "predicate": "dc:author",
      "subject": "(http://www.flickr.com/people/erroba/)"
    },
    {
      "object": "(http://www.flickr.com/photos/erroba/3007698875/)",
      "predicate": "dc:title",
      "subject": "[Eiffel tower, Paris, France :: Fisheye :: HDR]"
    },
    {
      "object": "(http://www.flickr.com/photos/erroba/3007698875/)",
      "predicate": "foaf:depicts",
      "subject": "(http://dbpedia.org/resource/Eiffel_Tower)"
    },
    {
      "object": "(http://www.flickr.com/photos/erroba/)",
      "predicate": "foaf:homepage",
      "subject": "(http://www.erroba.be/)"
    }
  ]
}
```

`http://www.flickr.com/photos/erroba/3007698875/` is the URI of a resource (here, a flickr post). The RDF precises what the resource depicts, what's its title, and who created it. The RDF precises the homepage of this author.


#### Consider now the knowledge represented in the snippet below. Explain what the machine will be able to infer regarding the first description. Draw the resulting RDF graphs displaying these inferences. (2013, 2014, 2015)

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

#### Consider the domain University domain where the concepts are: People, Organization, Publication, Professor, Student and Course and where the relationships are: author, worksFor and teach. Draw the graph that would represent this model in OWL. Provide the axioms corresponding to this model using the N3 syntax. (2013, 2014, 2015, 2016)

*Alternative formulation: mentions of the "giveECTS" property. Asks to show the hierarchy and the domain-range of properties.*


JSON representation of the graph:

```json
{
  "nodes": [
    "People",
    "Organization",
    "Publication",
    "Professor",
    "Student",
    "Course"
  ],
  "edges": [
    {
      "object": "Student",
      "predicate": "rdfs:subClassOf",
      "subject": "People"
    },
    {
      "object": "Professor",
      "predicate": "rdfs:subClassOf",
      "subject": "People"
    },
    {
      "object": "Professor",
      "predicate": "author",
      "subject": "Publication"
    },
    {
      "object": "Professor",
      "predicate": "author",
      "subject": "Course"
    },
    {
      "object": "Professor",
      "predicate": "worksFor",
      "subject": "Organization"
    },
    {
      "object": "Professor",
      "predicate": "teach",
      "subject": "Student"
    },
  ]
}
```

N3 axioms:

```n3
@prefix s: <http://the-university-domain.org/>.

s:Professor
  rdfs:subClassOf s:People;
  s:author s:Publication;
  s:author s:Course;
  s:teach s:Student.

s:Student
  rdfs:subClassOf s:People.
```


#### What are the algebraic properties available in OWL? (2013, 2014, 2015, 2016)

Class constructors: intersectionOf, unionOf, complementOf, oneOf, allValuesFrom, someValuesFrom, maxCardinality, minCardinality.

Axioms: subClassOf, equivalentClass, disjointWith, sameIndividualAs, differentFrom, subPropertyOf, equivalentPrperty, inverseOf, transitiveProperty, functionalProperty, inverseFunctionalProperty.


#### Give the name of two popular ontology editors. (2013, 2014, 2015, 2016)

Protégé, DOE (differential ontology editor), OilEd, WebOde, OntoEdit, Swoop, NeOn toolkit.


#### What is the name of the 3 methods to embed structured metadata in a web page? (2013, 2014, 2015)

* Open Graph Protocol
* SHOE: simple HTML ontology extension
* RDFa: RDF in attributes
* DAML+OIL: layered onto XML and RDF


#### Give the name of 3 popular semantic web vocabularies. (2013, 2014, 2015, 2016)

The top 5 according to PIC lov ranking:

1. dcterms: Dublin core vocab
2. schema
3. gr: good relation ontology
4. foaf: friend of a friend
5. bibo: bibliographic ontology


#### Consider that in the University domain described above, EURECOM is an instance of an Organization, that WebSem is an instance of a Course, and that RaphaelTroncy is an instance of Professor. Write in N3 those instances. (2013, 2015, 2016)

```n3
@prefix s: <http://the-university-domain.org/>.

:RaphaelTroncy rdf:type s:Professor.
:WebSem rdf:type s:Professor.
:EURECOM rdf:type s:Organization.
```

#### Provide the SPARQL query that will return all publications authored by the teacher of the WebSem course. (2013, 2015)

```sparql
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX s: <http://the-university-domain.org/>

SELECT ?pub
WHERE {
  ?t rdf:type s:Teacher .
  ?t s:teach s:WebSem .
  ?t s:author ?pub
}
```


#### What is the LOV project? Explain its main goal. (2014, 2015, 2016)

LOV = linked open vocabulary.

In a nutshell, its goal is to provide a tool to ease the use and re-use of vocabulary in the semantic web by providing a platform allowing people to search for terms and vocabularies.

It also provides relevance insights thanks to a ranking of terms (using IC: information content) and a ranking of vocabularies (using PIC: partitionned information content).


#### What is a dereferenceable URI? (2014, 2015)

These are URIs designed for linked data. These URIs represent the data that can be retrieved from the resource they locate, ie they don't represent the string of characters that they are.


#### Explain what are the NER and the NEL tasks in Information Extraction. (2015, 2016)

*Alternative formulation: same + give an example using a sentence*

NER: Named entity recognition. Subtask of information extraction that seeks to locate and classify named entities in text into pre-defined categories such as the names of persons, organizations, locations, expressions of times, quantities, monetary values, percentages, etc.

NEL: Named entity linking. Subtask of information extraction that determinines the identity of entities mentioned in text.

```
Paris is the capital of France
  |
  NER
  |
[Paris](noun) is the capital of France
  |
  NEL
  |
[Paris](noun -> city, not "Paris Hilton" or "Paris, Arkansas") is the capital of France
```


#### Explain what Part of Speech tagging means and why is it useful in Information Extraction. (2015)

It is the process of marking up words in a corpus to a particular part of speech. A part of speech is a category of lexical items (mostly words) which have similar grammatical properties, like nouns, verbs, and adjectives.

It allows to infer more informations from sentences:

```
I live in Paris
  |
  POST:
  if (<subject> <verb:live> in <noun>)
  then (<subject> <verb:live> in <noun that defines location>)
  |
I live in [Paris](location)
```


#### Give the name of 1 popular knowledge base. (2015)

YAGO (yet another great ontology): open-source KB that automatically extracts infos from Wikipedia and other sources.


#### Semantic Networks were popularized in the sixties by Collins and Quillian. What are the two families
of technologies that have been developed later and how do those relate to the Semantic Web? (2016)

* **Description logic**: Describe domain in terms of concepts (classes), roles (properties, relationships) and individuals. This is basically what RDF and triple data does with their object-predicate-subject paradigm.
* **Conceptual Graphs**: Graphs are at the basis of the linking of data in the semantic web.


#### Consider the turtle snippet below. Draw the resulting RDF graphs displaying those 3 entities.

```turtle
BASE <http://example.org/>
PREFIX foaf: <http://xmlns.com/foaf/0.1/>
PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
PREFIX schema: <http://schema.org/>
PREFIX dcterms: <http://purl.org/dc/terms/>
PREFIX wd: <http://www.wikidata.org/entity/>

<bob#me>
  a foaf:Person ;
  foaf:knows <alice#me> ;
  schema:birthdate “1990-07-04”^^xsd:date ;
  foaf:topic_interest wd:Q12418
wd:Q12418
  dcterms:title "Mona Lisa" ;
  dcterms:creator <http://dbpedia.org/resource/Leonardo_da_Vinci>
  <http://data.europeana.eu/item/04802/243FA8618938F4117025F17A8B813C5F9AA4D619>
  dcterms:subject wd:Q12418 .
```


(similar to questions above, ignored)


#### 