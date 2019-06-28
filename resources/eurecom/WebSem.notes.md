# Semantic Web and Information Extraction Technologies

Notes by Nino Filiu; based on the "WebSem" course by Raphael Troncy

| Slides file | completion status |
| --- | --- |
| [lecture 1](http://www.eurecom.fr/~troncy/teaching/websem2018/Lecture_1-05-03-2018.pdf) | done |
| [lecture 2](http://www.eurecom.fr/~troncy/teaching/websem2018/Lecture_2-12-03-2018.pdf) | done |
| [lecture 4](http://www.eurecom.fr/~troncy/teaching/websem2018/Lecture_4-26-03-2018.pdf) | done |
| [lecture 5](http://www.eurecom.fr/~troncy/teaching/websem2018/Lecture_5-14-05-2018.pdf) | done |
| [lecture 6](http://www.eurecom.fr/~troncy/teaching/websem2018/Lecture_6-28-05-2018.pdf) | done |

## Enhancing the web

### History

* **1945**: Vannevar Bush describes the memex system in his article As We May Think. Memex=memory+index: on the press of a numbered button, the memex retrieves the right card, and cards can reference each other (first hypertext).
* **1969**: Arpanet
* **1974**: TCP/IP
* **1984**: DNS
* **1989**: First proposal of the web by a CERN engineer, Tim Berners-Lee.
* **1992**: HTML
* **1996**: HTTP
* **2000s**: google, myspace, facebook, youtube, twitter, iphone

### Tools

The web is an application layer above the internet. At the beginning, the web was based on HTML, HTTP, and URL. Now, XML has been added to the family. XML=extensible markup language. Example:

```
<?xml version="1.0" encoding="ISO-8859-1"?>
<post_it>
 <urgent />
 <subject>plane tickets</subject>
 <date>2005-11-28</date>
 <message>your plane tickets are on my desk</message>
</post_it>
```

A document is said to be **well-formed** if it XML-compiles, a document is said to be **valid** if it is well-formed and complies with a DTD or XML schema. A **DTD (document type definition)** is a set of markeup declarations that defines the structure, the legal elements and the legal attributes of an XML document. DTD example:

```
<?xml version="1.0"?>
<!DOCTYPE note [
<!ELEMENT note (to,from,heading,body)>
<!ELEMENT to (#PCDATA)>
<!ELEMENT from (#PCDATA)>
<!ELEMENT heading (#PCDATA)>
<!ELEMENT body (#PCDATA)>
]>
<note>
<to>Tove</to>
<from>Jani</from>
<heading>Reminder</heading>
<body>Don't forget me this weekend</body>
</note>
```

* `!DOCTYPE note` defines that the root element of this document is note
* `!ELEMENT note` defines that the note element must contain four elements: "to,from,heading,body"
* `!ELEMENT to` defines the to element to be of type "#PCDATA"
* `!ELEMENT from` defines the from element to be of type "#PCDATA"
* `!ELEMENT heading` defines the heading element to be of type "#PCDATA"
* `!ELEMENT body` defines the body element to be of type "#PCDATA"

### The need for the semantic web

We can read web pages but most computers can't. What they see is the HTML and even if the HTML is really minimalistic, it's hard to differentiate the content from other stuff like menus/footers which contain less information. Even then, it's quite hard to extract the informations contained in the page.

The **Semantic Web** is an evolving extension of the World Wide Web in which the semantics of information and services on the web is defined. It is also called the **web 3.0** or the **Web of data**. The goal is not to make machines think like humans but rather to make the web understandable by machines.

The semantic web heavily relies on linked data and is growing exponentially: we're now at more than 100B triples, 1B links, and 1000 data sets

Several tools exists to search the web3.0 like Swoogle, Indice, Watson, and Falcons. Some governement have 3.0-ed some of their data: [data.gov.uk](https://data.gov.uk/), [data.gov (US)](https://www.data.gov/), [data.gouv.fr](https://www.data.gov.fr/)... Companies also hopped on the bangwagon, as we can see on [data.bfn.fr](http://data.bnf.fr/) for example.

Some principles:

**Closed world, open world**: classical systems like SQL database rely on the closed world assumption: if an object is not in the database, it does not exists. In the open world assumption, the absence of a triple is not significant. (image,creator,Raphael) does not mean that Raphael is the only creator of image, it simply means that Raphael belongs to the group of creators. The absence of such a tuple would not mean that the image does not have any creators.

**Linked data**: URIs are used to identify things with distributed ownership so that people can look up those things. Include RDF links to other URIs to enable discovery of related information.

### RDF: resource description framework

* **Resource**: everything than can have an URI.
* **Description**: attributes, features, and relations of the resources.
* **Framework**: model, languages and syntaxes for these desccriptions.

RDF uses an XML syntax. 

The web is based on triples: every piece of information can be broken down into a set of **(subject, predicate, object)** that can be seen as arcs of a graph. Example:

```
image.jpg has for creator Raphael and depicts the elephant Ganesh
↓
(image.jpg, creator, Raphael)
(image.jpg, depicts, Elephant Ganesh)
```

RDF resources and properties are identified by URIs, and values of properties can also be strings. In the example above, the related triples could be:

* (http://troncy.tumblr.com/post/1254385321, http://purl.com/dc/elements/1.1#creator, http://troncy.tumblr.com/)
* (http://troncy.tumblr.com/post/1254385321, http://xmlns.com/foaf/0.1#depicts, "Elephant Ganesh")

RDF allows for blank nodes, ie instead of the URI of image.jpg, a string like \_:x is generated. "There exists an image that respresents Ganesh" would have the following RDF:

* (\_:x, rdf:type, [image URI])
* (\_:x, [depicts URI], Ganesh)

But blank nodes break the graph in the way that they can't be reused, that is why it is a good practice to name your resources and to reuse them when possible.

In a nutshell, an RDF data model can be represented in three ways:

* XML encoding for machines
* triples for reasoning
* graph for human viewing

### Other serializations of RDF

XML is not the only serialization of RDF. Popular alternatives include N3, Turtle and JSON-LD. Three writings of the same information:

#### data.xml

```xml
<rdf:RDF
    xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
    xmlns:dc="http://purl.org/dc/elements/1.1/">
    <rdf:Description
        rdf:about="http://en.wikipedia.org/wiki/Tony_Benn">
        <dc:title>Tony Benn</dc:title>
        <dc:publisher>Wikipedia</dc:publisher>
    </rdf:Description>
</rdf:RDF>
```

#### data.n3

```n3
@prefix dc: <http://purl.org/dc/elements/1.1/>.
<http://en.wikipedia.org/wiki/Tony_Benn>
 dc:title "Tony Benn";
 dc:publisher "Wikipedia". 
```

#### data.json

```json
{
  "@context": {
    "dc": "http://purl.org/dc/elements/1.1/",
    "rdf": "http://www.w3.org/1999/02/22-rdf-syntax-ns#",
    "rdfs": "http://www.w3.org/2000/01/rdf-schema#",
    "xsd": "http://www.w3.org/2001/XMLSchema#"
  },
  "@id": "http://en.wikipedia.org/wiki/Tony_Benn",
  "dc:publisher": "Wikipedia",
  "dc:title": "Tony Benn"
}
```

### Data integration

In the semantic web, data mustt be integrable, ie it must be possible to merge datasets. Here's the framework on how to do so:

1. Map the data onto an abstract representation (ie: independant from its internal representation)
2. Merge the representations
3. Make queries on the whole

Example:

dataset A:

| ID | author | title |
| --- | --- | --- |
| ISBN1234 | x | The Glass Palace |

| ID | name |
| --- | --- |
| x | Amitav Ghosh |

dataset B:

| ID | titre | original |
| --- | --- | --- |
| ISBN4321 | Le palais des miroirs | ISBN1234 |

Merged mapped representations:

* ISBN1234 → a:author → x
* ISBN1234 → a:title → The Glass Palace
* x → a:name → Amitav Ghosh
* ISBN4321 → f:titre → Le Palais des miroirs
* ISBN4321 → f:original → ISBN1234

We know have mutual informations and questions like "what is the title of the original of 'Le Palais des Miroirs'?" can be answered.



## Knowledge representations

### Ontology

Philosophically, it is the study of the nature of being, basic categories of being, and their relations. Scientifically, it is a formal and explicit specification of a shared conceptualization. In computer science, it must contain:

* A **vocabulary** to describe some domain
* An explicit **specification** of the intented mmeaning of the vocabulary
* **Constraints** capturing additional knowledge

Ontology is not taxonomy (science of defining and naming groups): an ontology must contain a taxonomy, though.

Ontologies are useful to share common understandings among people or software agents. Meanings are specified with unique identifiers. People in the 60's began using graphs for their ontology, and description logics (DL) allowed for better descriptions. Domains are described in terms of:

* **concept/classes/types/categories**: unary predicates (one free variable)
* **roles/properties/relationships**: binary predicates (two free variables)
* **individuals/objects/instances**: constants

Many languages to describe ontologies are OO. Here's the 101 on how to build an ontology:

1. Define classes
2. Arrange in taxonomic hierarchy, ie subclasses and superclasses model
3. Define properties
4. Find values for properties in the instance of classes

### Description logics

Several proposition of DLs have been proposed, the simplest being ALC:

```
booleans    ⊓(AND), ⊔(OR), ¬(NOT)
quantifiers ∃, ∀

"person whose all children either are doctors or have a doctor children"
↓
person ⊓ ∀ hasChild( doctor ⊔ ∃ hasChild.doctor )
```

Several extensions to the ALC have been proposed, like:

```
H   role hierarchy      ex: hasDaughter ⊑ hasChild
I   inverse roles       ex: isChildOf ≡ hasChild^-1
N   number restrictions ex: ≽2hasChild
```

The most used extented ALC is SHIQ, which is ALC + several extensions like role hierarchy and inverse roles. SHIQ is the basis for OWL (web ontology language).

A **TBox** is a set of schema axioms, an **ABox** is a set of data axioms. A **knowledge base (KB)** is a TBox plus an ABox. Reasonning can be applied to gain further knowledge. eg:

```
KB: {
    TBox: {
        doctor ⊑ person,
        happyParent = person ⊓ ∀ hasChild( doctor ⊔ ∃ hasChild.doctor )
    },
    Abox: {
        John: happyParent,
        John.hashChild Josh
        Jane.hasChild Josh
        Josh: ¬ doctor
    }
}

reasonning:
    Josh.hasChild doctor
```

### Knowledge representation on the web

#### SHOE - simple HTML ontology extension

[examples here](http://w5.cs.uni-saarland.de/teaching/ws04/SemanticWebHTML/Vorlesung-04-11-04-SHOE.pdf)

#### DAML-ONT - darpa markup language for ontology

```
<Class ID="Animal">
 <label>Animal</label>
 <comment>This class of animals is illustrative of a
 number of ontological idioms.</comment>
</Class>
<Class ID="Male">
 <subClassOf resource="#Animal"/>
</Class>
<Class ID="Female">
 <subClassOf resource="#Animal"/>
 <disjointFrom resource="#Male"/>
</Class>
```

#### OIL - ontology inference layer

```
ontology-definitions
class-def primitive GeopoliticalObject
    subclass-of GeographicObject
class-def primitive Country
    subclass-of GeopoliticalObject
slot-def departureDate
    subslot-of tripReservationAttribute
    domain TripReservation
    range Date
```

In 2004 the W3C concluded into a recommandation to use OWL, which is more or less OIL+DAML. It is included here in the "semantic web cake":

<table style="text-align: center">
    <tr>
        <td rowspan="2">querying: SPARQL</td>
        <td>Ontology: OWL</td>
        <td>Rules: RIF+SWRL</td>
    </tr>
    <tr>
        <td colspan="2">taxonomy: RDFS</td>
    </tr>
    <tr>
        <td colspan="3">data interchange: RDF</td>
    </tr>
    <tr>
        <td colspan="3">syntax: XML</td>
    </tr>
    <tr>
        <td>Identifiers: URI</td>
        <td colspan="2">charset: UNICODE</td>
    </tr>
</table>

### RDFS

RDF schema. Provides primitives to write lightweight schemas for RDF triples, like primitives to define the vocabulary used in triples, and define elementary inferences. Classes of resources can be defines and organised. It allows for multiple inheritance for classes and properties. RDFS relations have structures: *domain→range* means that they can be defined only for elements of domains and to elements of range. Structures for relations are optional.

It looks like object-oriented programming, but an important difference is that properties are first-class citizens, ie they are not defined inside classes - they have their own hierarchy. Note also that properties can't be overwritten.

Example:

```xml
<rdf:RDF xml:base=   "http://inria.fr/2005/humans.rdfs"
         xmlns:rdf=  "http://www.w3.org/1999/02/22-rdf-syntaxns#"
         xmlns:rdfs= "http://www.w3.org/2000/01/rdf-schema#"
         xmlns=      "http://www.w3.org/2000/01/rdf-schema#">
         
    <Class rdf:ID="Man">
        <subClassOf rdf:resource="#Person"/>
        <subClassOf rdf:resource="#Male"/>
        <label xml:lang="en">man</label>
        <comment xml:lang="en">a male person</comment>
    </Class>
    <rdf:Property rdf:ID="hasMother">
        <subPropertyOf rdf:resource="#hasParent"/>
        <range rdf:resource="#Female"/>
        <domain rdf:resource="#Human"/>
        <label xml:lang="en">has for mother</label>
        <comment xml:lang="en">a female parent</comment>
    </rdf:Property>

</rdf:RDF>
```

RDFS also have a semantic: standard inference rules to derive additional triples from known statements:

```
IF ((c2 subClassOf c1) AND (x type c2)) THEN (x type c1)
IF ((c3 subClassOf c2) AND (c2 subClassOf c1)) THEN (c3 subClassOf c1)
IF ((p2 subPropertyOf p1) AND (x p2 y)) THEN (x p1 y)
IF ((p3 subPropertyOf p2) AND (p2 subPropertyOf p1)) THEN (p3 subPropertyOf p1)
IF ((p domain c) AND (x p y)) THEN (x type c)
IF ((p range c) AND (x p y)) THEN (y type c)
```

RDFS has a specific vocabulary:

* rdfs:Class
* rdfs:subClassOf
* rdfs:domain
* rdfs:range
* rdfs:subPropertyOf
* rdfs:Resource
* rdfs:Literal
* rdfs:Datatype
* rdfs:member
* rdfs:Container
* rdfs:ContainerMembershipProperty
* rdfs:comment
* rdfs:seeAlso
* rdfs:isDefinedBy
* rdfs:label

### OWL

OWL = web ontology language. An OWL ontology is a set of RDF statements, ie it "sits on top" of RDFS. A complete OWL reasonning is significantly more complex than a complete RDFS reasoner.

Class constructors:

| constructor | example |
| --- | --- |
| intersectionOf | Human ⊓ Doctor |
| complementOf | ¬ Male |
| oneOf | {john,mary} |
| allValuesFrom | ∀ hasChild.Doctor |
| someValuesFrom | ∃ hasChild.Lawyer |
| maxCardinality | ≤1 hasChild |
| minCardinality | ≥2 hasChild |

OWL also have axioms like "subClassOf" and "inverseOf".

### Building an ontology

Framework of process:

1. Define classes
2. Arrange in taxonomic hierarchy (subclasses and superclasses)
3. Define slots and properties
4. Describe their allowed values
5. Create instances of classes
6. Fill the property values of these instances

While doing so, ask yourself:

* What is the pupose of the ontology?
* What are the expected queries?
* Who would maintain the ontology?
* Can an exisiting ontology be reused?
* Will people use it?

Several tools exists to help one create an ontology - because writing by hand the XML is way too cumbersome and error prone:

* [Protégé](https://protege.stanford.edu/): software or web app to create ontologies and export them under many formats
* [LOV](http://lov.okfn.org/dataset/lov/): vocabulary+property database to look up which words are being used for what already
* [Schema](http://schema.org/): markup your web page so as to be processed by Google, Microsoft, Pinterest and the likes



## SPARQL

SPARQL is an RDF query language. Structure of a query:

```
# (optional) prefix declarations
PREFIX <prefixName>: <prefixURI>

# result clause
SELECT ?<varName>

# query pattern
WHERE {
    ...
}

# query modifiers
ORDER BY ?<varName>
LIMIT <int>
RANK <int>
```

| key word | description |
| --- | --- |
| SELECT | identifies the values to be returned |
| FROM | identifies the data sources to query |
| WHERE | triple graph pattern to be matched against in the RDF |
| PREFIX | declares the schema used in the query |
| FILTER | adds constraints |
| OPTIONAL | makes the matching of a part of the pattern optional |
| UNION | alternative patterns |
| ASK | check if there is at least one answer (replaces SELECT) |
| CONSTRUCT | returns an RDF graph instead of a response (replaces SELECT) |

### Some examples

Selects every name:

```
PREFIX foaf:  <http://xmlns.com/foaf/0.1/>
SELECT ?name
WHERE {
    ?person foaf:name ?name .
}
```

Note how URIs are written inside &lt; and &gt; signs but they differ from strings.

Everyone that has a name and an email address:

```
PREFIX foaf:  <http://xmlns.com/foaf/0.1/>
SELECT *
WHERE {
    ?person foaf:name ?name .
    ?person foaf:mbox ?email .
}
```

Homepage of max 50 persons known by Tim Berners-Lee:

```
PREFIX foaf:  <http://xmlns.com/foaf/0.1/>
PREFIX card: <http://www.w3.org/People/Berners-Lee/card#>
SELECT ?homepage
FROM <http://www.w3.org/People/Berners-Lee/card>
WHERE {
    card:i foaf:knows ?known .
    ?known foaf:homepage ?homepage .
}
LIMIT 50
```

All landlocked countries with a population greater than 15 million:

```
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>        
PREFIX type: <http://dbpedia.org/class/yago/>
PREFIX prop: <http://dbpedia.org/property/>
SELECT ?country_name ?population
WHERE {
    ?country a type:LandlockedCountries ;
             rdfs:label ?country_name ;
             prop:populationEstimate ?population .
    FILTER (?population > 15000000) .
}
```

Selects adults, as defined by either have an adult property or are >18yo:

```
PREFIX ex: <http://inria.fr/schema#>
SELECT ?name
WHERE {
  ?person ex:name ?name .
  {
    { ?person rdf:type ex:Adult }
    UNION
    { ?person ex:age ?age FILTER (?age >= 18) }
  }
}
```

[More examples...](https://www.w3.org/2009/Talks/0615-qbe/)

#### The negation-as-a-failure trick

Persons who are not known authors:

```
PREFIX ex: <http://inria.fr/schema#>
SELECT ?name
WHERE {
  ?person ex:name ?name .
  OPTIONAL { ?person ex:author ?x }
  FILTER (!bound(?x))
} 
```

In the case that `?person` has no `ex:author` property, the `OPTIONAL { ?person ex:author ?x }` does not stops the query from matching against `?person` but it creates an unbound value `?x`, which can be manipulated for further testing. This can be tricky as one can be tempted to write the following to check if a developer does not knows Java:

```
PREFIX ex: <http://inria.fr/schema#>
SELECT ?name
WHERE {
  ?person ex:name ?name .
  ?person ex:knows ?x .
  FILTER ( ?x != "Java" )
}
```

Instead, if a person knows both Python and Java, they will be *selected*. The correct way to query is this:

```
PREFIX ex: <http://inria.fr/schema#>
SELECT ?name
WHERE {
  ?person ex:name ?name .
  OPTIONAL {
    ?person ex:knows ?x .
    FILTER (?x=="Java")
  }
  FILTER (!bound(?x))
}
```

Another query that works uses the **MINUS** keyword which is pretty self-explanatory:

```
PREFIX ex: <http://inria.fr/schema#>
SELECT ?name
WHERE {
  ?person ex:name ?name .
  MINUS {
    ?person ex:knows ?x FILTER (?x=="Java")
  }
}
```

### Web

There exists many endpoints for SPARQL queries on the web, see [the lab on SPARQL for more](WebSem.lab.sparql.md).



## Information extraction

### Language

Goal: recognize and link the name of an entity. For example, from the entity "Armstrong", deduce its knowledge : `is a -> human, works for -> NASA` etc. Lots of difficulties:

* Language is naturally ambiguous: Neil Armstrong or Lance Armstrong?
* Net language is unconventional (emojis, abbreviations, ...)

Language analysis programs are quite complex, but here's a few of the tools they use:

* Tokenization: separate into words
* Grammatical tagging at token level (eg: the 3rd word is an adjective)
* Supervised learning algorithms exploiting the tokens and their relations
* Grammar-based assumptions (see below)
* Stats-based assumptions (see below)

Grammar-based logic:

```
rule: "on" + "the" + x => x a location
sentence: "Armstrong landed the eagle on the moon"
inference: "moon" is a location
```

Stats-based logic:

```
Joshua    received an MSc    at Harvard
subject   verb     object    location
Armstrong landed   the eagle on the moon
```

NERD is a tool that does named entity recognition and disambiguation (thus the name).

To go further, sentiment analysis have to be performed.

### Video

Can video be divided into meaningful segments? How can these fragments be described? How to evaluate their relevance? How to find related documents that complete the video?

That is exactly what TED does, cf [their website](https://www.ted.com/talks/ken_robinson_says_schools_kill_creativity/reading-list).

## Web integration

### SKOS

Simple knowledge organization system

Application of RDFS/OWL to make an ontology of concepts.

Concepts are labelled (preferred, alternative, hidden) and linked (related, broader, narrower). Notes exists for documentation purpose (scopeNote, definition, example, historyNote, editoralNote, changeNote).

```
inria:CorporateSemanticWeb
    rdf:type skos:Concept;
    skos:prefLabel "Corporate semantic web"@en;
    skos:altLabel "CSW"@en;
    skos:broader w3c:SematicWeb;
    skos:scopeNote "only within knowledge management community".
```

Schemes are compiled sets of concepts. Concepts from difference schemes are linked (exactMatch, relatedMatch, broadMatch, narrowMatch).

```
inria:CorporateSemanticWeb
    skos:inScheme inria:ResearchTopics.
```

### RDB2RDF

Converts a database into RDF triples. It either does it on the storage or acts as a in-memory wrapper above the RDB.

Other tools exists to do so, like R2RML, RML, Ultrawrap, Morph...

OpenRefine, previously known as Google Refine, helps cleans the data and has got tools to make it RDF-compatible. Not only can you output your DB as RDF, you can also for example make a whole column match the Wikipedia spelling of the content.

### Data reconcilitation

Have different names according to the community. Goal: generate relevant owl:sameAs (and related) links based on similarity evaluation. Once normalization has been done...

```
country Germany -> country germany
country GERMANY -> country germany
foaf:givenName "Bob", foaf:surname "Dylan" -> foaf:name "Bob Dylan"
```

...similarity can be computed, but it's often quite hard of a problem to produce relevant matching (ie: a lot of true positive/negative, a few false positive/negative). Here's two simple string matching techniques nevertheless:

* **Levenshtein measure**: aka minimum edit measure. edit = insert/delete/replace.
* **Token-based measures**: token = word, enables to bring "Mr John Doe" and "Doe, John" closer. Jaccard coefficient: `J(a,b) = len(a inter b) / len(a union b)`.

### Geodata

Localisation of events and places is relevant when it comes to posting relevant informations on the web3.*what are all the bridges in a 2km radius around the Eiffel tower?*. Properties like `prop-fr:ouestOf, prop-fr:superficie, prop-fr:altMaxi` thus exists.

31 datasets are geo-oriented and they account for 1 in 5 triples. They are based on geometry (top-level geometry type) and geographic features (representation of a real-world phenomenon).

### Social networks

Social medias are isolated communities of users and their data. We need ways to redesign networks so as to ease the data transfer from one community to another, eg: *move all my wordpress posts and comments to squarespace*.

The goal is to have a single identity represented by RDF data that have different views according to the social network. To get there, it is required to use agreed-upon semantics to describe content. That was the goal of [FOAF](https://en.wikipedia.org/wiki/FOAF_(ontology), an ontology describing persons, their activities and their relations to other. People can create their own FOAF document.

SIOC is an ontology linking FOAF and SKOS. It was created because *people* (foaf) produce *content* (skos) and neither skos nor foaf covers the whole relevance of this affirmation. Twitter, Facebook, Wordpress and FLickr already produce ready-to-consume SIOC data.

Facebook is however pushing more the [open graph protocol](http://ogp.me/). The homepage explains the project pretty well and the schema is available [here (turtle schema)](http://ogp.me/ns/ogp.me.ttl). Basically the goal is to be able to turn any webpage into a rich social object, simply thanks to `<meta property="og:XXX" content="YYY">` in the `<head>` of HTML files. The facebook graph api (to access it, register and replace the www by graph in a facebook URL) is a REST API that eases the data access from machines to facebook.

### Vocabulary linkage

LOV is a service that puts together 420 vocabularies so as to fuel the reuse of vocabularies.

It is also working on a proposal of ranking its vocabulary:

* terms are ranked according to their IC (information content): `IC(term) = -log2(phi(term)/maxPhi)`. Top 5 terms: `skos:example, dce:contributor, skos:scopeNote, dcterms:source, mads:code`.
* vocabularies are ranked according to their PIC (partitionned information content): `PIC(vocab) = weight(vocab) * sum([IC(t) for t in vocab.terms])`. The weight is defined as the number of links that involve terms of this vocabulary. Top 5 vocabularies: `dcterms, schema, gr, foaf, bibo`.

### Kowledge graph embedding

How similar is *pizza* to *pasta*? *King* is to *queen* what *man* is to *\_\_\_*? Such simple questions for a human are actually quite complicated for computers but graph calculations on linked data can help with that. Moreover, the applications of the answers are many.

Let's use Netflix, which collects infos about its users and their film feedbacks so as to recommend films to its users. By playing on the graph structure (eg: tightly connected entities are more related) and semantics (eg: `mainActor` is a more important property than `assistantPhotographer`), some algorithms can predict how well some films matches a user. An approach to do so is to map the vertices of a graph to points in space and calculating the distance between them.