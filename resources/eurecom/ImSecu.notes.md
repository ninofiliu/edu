# Imaging security

Notes by Nino Filiu; based on the [ImSecu](http://www.eurecom.fr/en/course/ImSecu-2018Spring) course by [Jean-Luc Dugelay](http://www.eurecom.fr/en/people/dugelay-jean-luc).

| Slides file | completion status |
| --- | --- |
| [Biometrics](https://my.eurecom.fr/upload/docs/application/pdf/2018-05/introbiom18-slides.pdf) | done |
| [Watermarking](https://my.eurecom.fr/upload/docs/application/pdf/2018-05/intro-watermarking2018-slides.pdf) | done |
| [Forensics](https://my.eurecom.fr/upload/docs/application/pdf/2018-05/introdif-april2018-slides.pdf) | done |
| [Video surveillance](https://my.eurecom.fr/upload/docs/application/pdf/2018-05/videosurveillancerefdoc15mai2018.pdf) | done |

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

Watermarking 3D objects is harder but still possible. Firstly, there are several categories for 3D modelling, the most popular being mesh-based modelling, degradationless data modification can be applied to it:

* Modify the order of the triangles
* Modify the order of triplets in the triangle
* Modify the organisation of the data storage
* Transform one triangle into 4, encode infos in the position of points

But data modification can be applied to geometrical data (eg modify point positions). The trick is that most 3D models pass through a mesh simplification so as to reduce its storage and memory size, but it can also overwrite the watermark in the same way that image compression can often overwrite image watermarking.



## Forensics

Goal: detection of modifications, authentication, and hardware identification.

### Hardware identification

```
(image)
   |
   V
[lens, filters]     | acquisition artifacts
[CFA]               |
[Imaging sensor]    |
[CFA interpolation] |
   |
   V
[post-processing]   | processing artifacts
[digital image]     | 
[storage]           | 
```

*CFA: color filter array, a RGB pixel filter that allows light-sensible pixels to be sensible to only R, G , or B*

When uniform light falls on a camera sensor, each pixel should output exactly the same value. Small variations in cell size and substrate material result in slightly different output values. The difference between the true response from a sensor and a uniform response is known as **Photo Response Non Uniformity (PRNU)**. It is caused by the physical properties of the censor itself and is often used to identify the censor because:

* All censors exhibit a unique PRNU
* Lens-independent
* Content-independent
* Environment-independent
* Survives post-processing
* PRNU is present in every non-dark picture

The framework of action to obtain an approximation of the PRNU is:

1. Obtain low-frequency images (ie containing large, slowly varying zones like sky or walls)
2. De-noise
3. Subtract the denoised pictures with the original to get noise residuals
4. Average noise residuals to get a PRNU estimate

Correlate-compare this PRNU with the noise residual of an image to see if there is a match.

However, this technique only identifies a particular sensor, and often a camera model identification is more handy, thanks to the **digital image processor (DIP)**, the camera post-censor component that implements demosaicing algorithms to recover missing infos, perform white balancing, gamma correction, and the likes. For example, based on the fact that there exists several **Bayer patterns** for the CFA, there exists different demosaicing algorithms, and artifacts that goes along with them.



### Tamper detection

With modern softwares, it is now very easy to perform convincing image and video manipulations.

There is however two main types of tampering: these which involve a single image and these which involve more. Ther firsst is detected thanks to algorithmic techniques:

* Differences in demosaicking
* Differences in chromatic aberrations
* Detection of image scaling and rotation

And advanced techniques:

* Inconsistencies in eye reflexes
* Inconsistencies in lightning (handy when the eye in the HD because it's a sphere)

For single-image manipulation:

* duplicated region detection: on small blocks, do a PCA and detect which small block matches
* Detect DCT coefficient artifacts for double JPEG compression

CGI can also be used in image modification. In this case, one can often see an absence of hardware artifacts.



## Video surveillance

### 101

Video surveillance application are mostly used to remotely record what is happening for legal archive or remote surveillance, but further applications can be used:

* car plate identification to modelize traffic
* automatic abnormal event detection
* marketing statistics in shopping malls
* person recognition
* people counting and crowd flows estimations

Along with different uses are different camera types:

* fixed camera
* pan/tilt/zoom (PTZ)
* night vision (near infrared + near infrared illuminators)
* thermal cams
* omniview aka 360 cameras
* RGBD (rgb + depth) thanks to time of flight estimations, or kinect-like (that use infrared redshift/blueshift)

However most of these applications follow the same framework: `acquisition -> processing -> visualization`.

### Issues

The highest cost for surveillance is wiring, so IP transmission seems tempting, however using IoT for security is a widely considered as a bad move, because it is prone to MiTM and other tampering.

Heterogeneous networks: even if there are a lot of CCTVs available, the fact that they are not interconnected and don't work together in an intelligent way reveals uneploited capacitites.

**Proactive surveillance** (agents monitor screens, detect events and react) is more costly than **reactive surveillance** (agents react upon event reporting) but also more efficient. Automation can help making reporting more reliable so as to shift as much as possible to a reactive surveillance while still maintening security standards.

Cameras often record a high amount of useless data. Storage is not that expensive, but human processing after an even is: after big events, policemen often work for days just to manually observe hours and hours of footage. In Britain, there is [one CCTV for every 14 people](https://www.telegraph.co.uk/technology/10172298/One-surveillance-camera-for-every-11-people-in-Britain-says-CCTV-survey.html). Hacking of storage facilities also poses a threat.

The saint-graal of surveillance is to design a system that both protect the privacy of people while still performing required tasks. Example of techniques aiming to perform such thing:

* total automation
* encrypt privacy-sensitive image/video zones, like windows of the neighbors (aka region of interest (ROI))
* de-identifying faces


### Techniques

#### Foreground/background distinction

Basic approaches (I=image, FG=foreground, BG=background)

* frame differencing: `FG(t) = abs(I(t)-I(t-1))>treshold ? I(t) : false`
* median: `BG(t) = median([BG(i) for i in [0:t]])`
* average: `BG(t) = 0.05*I(t-1) + 0.95*B(t-1)`

More advanced approaches uses gaussian mixtures and eigenbackgrounds.

#### Body and face detection

Oriented gradients help build a bounding box for body detection.

Viola-Jones, AdaBoost, and Haar features are often combined for face detection.

Often, the main issue is face occlusion (glasses, hat).

#### People tracking

Note that most of the time, we want people re-identification (for tracking people automatically) rather that precise identification. In this case, soft biometrics are often used (height, gait).

Spatial tracking algorithms often use a predict-correct loop for tracking (prediction for example based on the fact that people mostly move linearly and uniformly).