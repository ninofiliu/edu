# Imaging security

Notes by Nino Filiu; based on the [ImSecu](http://www.eurecom.fr/en/course/ImSecu-2018Spring) course by [Jean-Luc Dugelay](http://www.eurecom.fr/en/people/dugelay-jean-luc).

| Slides file | completion status |
| --- | --- |
| [Biometrics](https://my.eurecom.fr/upload/docs/application/pdf/2018-05/introbiom18-slides.pdf) | done |
| [Watermarking](https://my.eurecom.fr/upload/docs/application/pdf/2018-05/intro-watermarking2018-slides.pdf) | stopped at the 3D watermarking |
| [Forensics](https://my.eurecom.fr/upload/docs/application/pdf/2018-05/introdif-april2018-slides.pdf) | to do |
| [Video surveillance](https://my.eurecom.fr/upload/docs/application/pdf/2018-05/videosurveillancerefdoc15mai2018.pdf) | to do |

## Biometrics

### Why biometrics?

There exists three types of authentication, that can be combined for further security:

* something you know (ex: password)
* something you have (ex: key)
* something you are (ex: fingerprints)

Traditional identification (knowledge-based or token-based) has weaknesses: PIN may be forgotten, physical keys may be stolen/lost/copied, etc. Biometrics solves this by performing authentication based on pattern recognition which establishes the authenticity of a physiological or behavioral user's characteristic. In a nutshell, it **identifies you based on who you are or what you do**.

### Biometrics 101

Two stages to identification: enrollment + verification = who am I + am I really this person, just like username + password.

There exists several biometric identifiers: fingerprint, voice, image, hand geometry, retina, iris, keystroke dynamics, DNA, gait, wrist and hand veins, etc.

Two numbers: **FRR (false rejection rate)** and **FAR (false acceptance rate)**. Based on the decision treshold choice, one is forced to face a trade-off between the two depending on the policy. High security→(high FRR, low FAR), low security→(low FRR, high FAR). However, even if these rates are really low, the efficiency depends on the impostors rate, as the false positive paradox shows.

The Bertillonage was a technique invented by a french policeman that groups people based on their physical measurement in order to ease identification (around 1000 categories => identification burden divided by 1000). It was a step forward but not really reliable and quite cumbersome still.

Fingerprints began to be used in the 1900': odds of same fingerprints = 1 in 67 billions. Prints are clustered in classes based on their global patterns (arch, left loop, right loop, whorl). Local ridge characteristics aka minutiae (bifurcation, ending, island, lake, bridge) determines the uniqueness of a fingerprint. Adjustment is done by superposing around 15 minutiae

Video recognition call for new opportunities: visual speech, behavior analysis, dynamic facial expression...

IR imaging can be used too because the facial heat emission patterns can used to characterize a person. Unique and stable over time but blocked by glasses.

Face recognition is challenging because it must take into account the variability in the expression, lighting, and face dressing. The two main successful families of algorithms in this field are eigenvectors-based algorithms and elastic graph matching.

The ICAO adopted three vectors of recognition: face, fingerprint and iris. Note that multimodal identification is often used in order to combine the perks of a method with another. Combining methods requires to have a policy to combine the results: and/or/SVM combination? FRR/FAR considerations?

### Eigenfaces and fisherfaces

Usually designed for compression, ex go from a 65536 pixel space to a 200 face space for 256\*256px pictures and 200 faces.

We reduce efficiently the dimension by performing the PCA (principal component analysis): eigenvectors are ordered by the magnitude of their contribution to the variation between the training images. This is donne by ordering the eigenvalues from the largest to the smallest. Other space reduction methods can be used - see below.

Faces are identified by projecting them on the training face space, four possible cases with four comments:

```
            near face space
                   ᴧ
unknown individual | true positive
                   |
          ---------+---------> near face class
                   |
        not a face | false positive
                   |
```

PCA minimises the least-square error when approximating a face, thus providing an efficient approximation set of faces, but it is not taylored for face identification. LDA (linear discriminant analysis) is an algorithm proposed by Fisher that selects the eigenvectors that do the better job at separating the classes (ie the persons to identify). The eigenvectors produced by the PCA are called eigenfaces and the eigenvectors produced by the LDA are called fisherfaces.

### Performance

Some numbers for state-of-the-art identification techniques:

| Technique | FRR | FAR |
| --- | --- | --- |
| fingerprint | 0.2% | 0.2% |
| face | 10-20% | 0.1-20% |
| text-dependant speak | 1-3% | 1-3% |
| text-independant speak | 10-20% | 2-5% |

Increased performance can be achieved through the fusion of several identification techniques.



## Watermarking

Multimedia watermarking is the act of hiding a message inside an image, and audio, or a video. It allows for:

* Proof of ownership
* Tampering detection
* Tampering recovery

Watermaking scheme (signature = key):

```
             key                                  key (optional)
              |                                         |
original --[signer]--> watermarked --[corruption]--[retriever]--> retrieved
```

Several extraction modes:

* **Blind**: document → what is the signature?
* **Semi-blind**: document + signature → is that signature in the document?
* **Non-blind**: document + original (+ signature) → whatever

A good watermarking scheme is a one that satisfies the following criterias:

* **high capacity**
* **low perceptibility**
* **high reliability** = high robustness + low false alarm rate
* **speed**
* **cascading**
* **statistical undetectability**
* **asymmetry**

When distributing copyrighted material, a unique watermark is often added. In the case of a leak, the faulty user can be located.

When watermaking video, the following techniques are used:

* photometric: noise addition, gamma correction, ...
* spatial desynchronization: positional jitter, ...
* temporal desynchronization: changes of frame rate
* editing: cut and splice, graphic overlays ...

