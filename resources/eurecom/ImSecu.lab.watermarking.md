# ImSecu - Lab on watermarking

```
By            Nino FILIU
Network path  T:\courses\image\TpWatermarking\public\VC2010
Local path    U:\VC2010
```
## Exercise 1

#### Complete the functions `InsertNoiseLSB` and `ExtractNoiseLSB`.

```cpp
int CImage::InsertNoiseLSB(){
	int noise;
	srand(0);
	for (int c=0; c<nb_comp;c++) {
		if (C[c]==NULL)
			return ERROR;

		// EXERCISE 1
		for (int x=0; x<width; x++){
			for (int y=0; y<height; y++){
				SETBIT(C[c][x+width*y],0,rand()%2);
			}
		}

	}
	return SUCCESS;
}

int CImage::ExtractNoiseLSB(){
	int noise;
	srand(0);
	for (int c=0; c<nb_comp;c++) {
		if (C[c]==NULL)
			return ERROR;	
     
		// EXERCISE 1
		for (int x=0; x<width; x++){
			for (int y=0; y<height; y++){
				if (GETBIT(C[c][x+width*y],0)==rand()%2){
					// the LSB corresponds to the predicted value, set the color to its minimum value
					C[c][x+width*y]=0;
				} else {
					// the LSB doesn not corresponds to the predicted value, set the color to its maximum value
					C[c][x+width*y]=255;
				}
			}
		}

	}
	return SUCCESS;
}
```

#### Comment the results. What are the pros and cons in terms of visibility and reliability? Justify.

With XnView, I did three alterations on the bird picture:
* heavy hue shift (top right)
* slight hue shift (bottom right)
* slight luminosity shift of value -1 (bottom left)

Results below:

| original | tampered | checked
| --- | --- | --- |
| ![img](https://raw.githubusercontent.com/ninofiliu/perso/master/eurecom/media/bird.jpg) | ![img](https://raw.githubusercontent.com/ninofiliu/perso/master/eurecom/media/bird_LSB_tampered.jpg) | ![img](https://raw.githubusercontent.com/ninofiliu/perso/master/eurecom/media/bird_LSB_tampered_checked.jpg) |

Thanks to `ExtractNoiseLSB` the zones where tampering happened are found, but a counter-intuitive phenomenon happened: the heavily tampered zone appears less tampered (=less white) than the lightly tampered zone from the bottom left. This is because this type of watermarking only locates shifts in the LSB: it is possible to have a big change that doesn't affect LSBs and small changes that greatly affect LSBs.

The PSNR ratio of this method is pretty high, as the 'noise' only affects the LSB, so the visibility is good:

```
MSE = average((Original(x,y)-Noisy(x,y))^2)
    = 0.5*1 + 0.5*0
    = 0.5
MAX = 255
PNSR = 10*log10(MAX^2/MSE) = 51.14
```

However this method is not secure, as it only checks for modifications of LSBs - as steganographic techniques shows, it is [possible and quite easy](https://www.cs.bham.ac.uk/~mdr/teaching/modules03/security/students/SS5/Steganography_files/image022.jpg) to change completely the appearance of a picture if we can manipulate only the few most significant bits.

Moreover, authentication is independant from the considered image, so counterfeiting is easy once the method of watermarking is known.

## Exercise 2

#### Complete the functions `InsertCRCLSB` and `ExtractCRCLSB`.

```cpp
int CImage::InsertCRCLSB(){
	int nb_xblocks = width/8;
	int nb_yblocks = height/8;
	unsigned long crc;
	unsigned long *crcTable;
	short bit;
	int val; // don't forget to add this too
	
	CRCTable(&crcTable);

	for (int c=0; c<nb_comp;c++) {
		if (C[c]==NULL)
			return ERROR;	

		for (int i=0; i<nb_xblocks; i++)
			for (int j=0; j<nb_yblocks; j++) {

				// EXERCISE 2 - compute CRC
				crc = 0xFFFFFFFF;
				for (int k=0; k<8; k++){
					for (int l=0; l<8; l++) {
						val = (int)C[c][8*i+k + (8*j+l)*width];
						SETBIT(val,0,0);
						crc = ((crc>>8) & 0x00FFFFFF) ^ (crcTable[ (crc^val) & 0xFF ]);
					}
				}
				crc=crc^0xFFFFFFFF;

				// EXERCISE 2 - set the LSB according to the CRC
				for (int k=0; k<32; k++){
					/*	k			index of crc bit that will be set
						(8*i+k%8)	x-position of the bit to set
						(8*j+k/8)	y-position of the bit to set */
					bit=crc%2;
					SETBIT(
						C[c][(8*i+k%8) + width*(8*j+k/8)],
						0,
						bit
					);
					crc=crc/2;
				}

			}
	}

	free(crcTable);
  
	return SUCCESS;
}

int CImage::ExtractCRCLSB(){
	int nb_xblocks = width/8;
	int nb_yblocks = height/8;
	unsigned long crc, xcrc;
	unsigned long *crcTable;
	short bit;
	int val; // don't forget to add this too

	CRCTable(&crcTable);
	
	for (int c=0; c<nb_comp;c++) {
		if (C[c]==NULL)
			return ERROR;	

		for (int i=0; i<nb_xblocks; i++){
			for (int j=0; j<nb_yblocks; j++) {	

				// EXERCISE 2 - compute the CRC (from the 7MSBs)
				crc = 0xFFFFFFFF;
				for (int k=0; k<8; k++){
					for (int l=0; l<8; l++) {
						val = (int)C[c][8*i+k + (8*j+l)*width];
						SETBIT(val,0,0);
						crc = ((crc>>8) & 0x00FFFFFF) ^ (crcTable[ (crc^val) & 0xFF ]);
					}
				}
				crc=crc^0xFFFFFFFF;

				// EXERCISE 2 - extract the CRC (from the LSBs)
				xcrc=0;
				for (int k=31; k>=0; k--){
					bit=GETBIT(C[c][(8*i+k%8) + width*(8*j+k/8)],0);
					xcrc=xcrc*2+bit;
				}

				// EXERCISE 2 - compare and take action
				if (crc!=xcrc){
					DrawBadBlock(8*i,8*j,8,8);
				}
			}
		}
	}

	free(crcTable);
  
	return SUCCESS;
}
```

#### Watermark, alter, and check an image.

Modifications:
* bottom: big hue shift
* top: small luminosity shift

| Original | Watermarked | Tampered | Checked |
| --- | --- | --- | --- |
| ![img](https://raw.githubusercontent.com/ninofiliu/perso/master/eurecom/media/yose.jpg) | ![img](https://raw.githubusercontent.com/ninofiliu/perso/master/eurecom/media/yose_CRC.jpg) | ![img](https://raw.githubusercontent.com/ninofiliu/perso/master/eurecom/media/yose_CRC_tampered.jpg) | ![img](https://raw.githubusercontent.com/ninofiliu/perso/master/eurecom/media/yose_CRC_tampered_checked.jpg) |

#### Comment the results.

This CRC watermarking preserve the visibility: the noise is only added to the LSB so the PSNR is lower or equal to the one generated by the method above.

In terms of security, we observe that even slight changes get noticed, as for the top zone where only a small luminosity shift has been made. Moreover, in order to alter an 8x8 block while bypassing the CRC checking, an attacker has to alter it it a way where the CRC matches for each component.

But given that the CRC is 32-bits long, and given that the attacker can only tweak 7x8x8=448 bits per block per component, the attacker would have to give up on a high PSNR to counterfeit an image. More precisely, if we assume that the CRC is random - which it kind of does on large samples of bits - the noise would account for 1 over 14 bits (=32/448), plus the LSB that can't be changed because the CRC goes there.

## Exercise 3

#### Complete the functions `BlockMean`, `InsertSelfEmbeddingLSB`, and `ExtractSelfEmbeddingLSB`.

```cpp
int CImage::BlockMean(short *value, int c, int posx, int posy, int block_width, int block_height){
	if (C[c]==NULL)
		return ERROR;
	if (posx<0 || posy<0 || posx+block_width-1>width
		 ||posy+block_height-1>height)
		return ERROR;
	
	// EXERCISE 3
	long int sum=0;
	int x,y;
	for (x=posx;x<posx+block_width;x++){
		for (y=posy;y<posy+block_height;y++){
			sum+=C[c][x+width*y];
		}
	}

	(*value) = (short)(sum/(block_width*block_height));
	return SUCCESS;
}

int CImage::InsertSelfEmbeddingLSB(){
	int nb_xblocks = width/8;
	int nb_yblocks = height/8;
	unsigned long crc;
	unsigned long *crcTable;
	short bit;
	short m[4];
	int ti,tj;
	int val;
	
	CRCTable(&crcTable);
	ti = 4;
	tj = 4;

	for (int c=0; c<nb_comp;c++) {
		if (C[c]==NULL)
			return ERROR;	

		for (int i=0; i<nb_xblocks; i++)
			for (int j=0; j<nb_yblocks; j++) {

				// EXERCISE 3 - insert the CRC in the first 32 LSB of the block (same as in InsertCRCLSB)
				crc = 0xFFFFFFFF;
				for (int k=0; k<8; k++){
					for (int l=0; l<8; l++) {
						val = (int)C[c][8*i+k + (8*j+l)*width];
						SETBIT(val,0,0);
						crc = ((crc>>8) & 0x00FFFFFF) ^ (crcTable[ (crc^val) & 0xFF ]);
					}
				}
				crc=crc^0xFFFFFFFF;
				for (int k=0; k<32; k++){
					bit=crc%2;
					SETBIT(
						C[c][(8*i+k%8) + width*(8*j+k/8)],
						0,
						bit
					);
					crc=crc/2;
				}

				// EXERCISE 3 - compute the average level for the 4 sub-blocks
				BlockMean(&m[0],c,8*i  ,8*j  ,4,4);
				BlockMean(&m[1],c,8*i+4,8*j  ,4,4);
				BlockMean(&m[2],c,8*i  ,8*j+4,4,4);
				BlockMean(&m[3],c,8*i+4,8*j+4,4,4);

				// EXERCISE 3 - set the last 32 LSB of another block according to these means
				for (int k=0; k<4; k++){ // k = sub-block index
					for (int l=0; l<8; l++){ // l = index of bit written
						bit=GETBIT(m[k],l);
						SETBIT(C[c][ ((8*(i+ti)+l)%width) +width* ((8*(j+tj)+k+4)%height) ],0,bit);
					}
				}

			}
	}

	free(crcTable);

	return SUCCESS;
}

int CImage::ExtractSelfEmbeddingLSB(){
	int nb_xblocks = width/8;
	int nb_yblocks = height/8;
	unsigned long crc, xcrc=0;
	unsigned long *crcTable;
	short bit;
	short m[4]; m[0]=0; m[1]=0; m[2]=0; m[3]=0;
	int ti,tj;
	int val;

	CRCTable(&crcTable);
	ti = 4;
	tj = 4;
	
	for (int c=0; c<nb_comp;c++) {
		if (C[c]==NULL)
			return ERROR;	

		for (int i=0; i<nb_xblocks; i++)
			for (int j=0; j<nb_yblocks; j++) {

				// EXERCISE 3 - checks for tampering, as in ExtractCRCLSB
				crc = 0xFFFFFFFF;
				for (int k=0; k<8; k++){
					for (int l=0; l<8; l++) {
						val = (int)C[c][8*i+k + (8*j+l)*width];
						SETBIT(val,0,0);
						crc = ((crc>>8) & 0x00FFFFFF) ^ (crcTable[ (crc^val) & 0xFF ]);
					}
				}
				crc=crc^0xFFFFFFFF;
				xcrc=0;
				for (int k=31; k>=0; k--){
					bit=GETBIT(C[c][(8*i+k%8) + width*(8*j+k/8)],0);
					xcrc=xcrc*2+bit;
				}

				// EXERCISE 3 - in case of tampering, partially reconstruct the tampered block
				if (crc!=xcrc){

					// retrieve the means
					for (int k=0; k<4; k++){ // k = sub-block index
						for (int l=0; l<8; l++){ // l = index of bit retrieved
							bit=GETBIT(C[c][ ((8*(i+ti)+l)%width) +width* ((8*(j+tj)+k+4)%height) ],0);
							SETBIT(m[k],l,bit);
						}
					}

					// repair the tampered block
					DrawFlatBlock(c,8*i  ,8*j  ,4,4,m[0]);
					DrawFlatBlock(c,8*i+4,8*j  ,4,4,m[1]);
					DrawFlatBlock(c,8*i  ,8*j+4,4,4,m[2]);
					DrawFlatBlock(c,8*i+4,8*j+4,4,4,m[3]);
				}
			}
	}
	free(crcTable);
	return SUCCESS;
}
```

#### Watermark, alter, and restore an image.

Modifications:
* bottom: big hue shift
* top: small luminosity shift

| Original | Watermarked | Tampered | Restored |
| --- | --- | --- | --- |
| ![img](https://raw.githubusercontent.com/ninofiliu/perso/master/eurecom/media/voile.jpg) | ![img](https://raw.githubusercontent.com/ninofiliu/perso/master/eurecom/media/voile_Self.jpg) | ![img](https://raw.githubusercontent.com/ninofiliu/perso/master/eurecom/media/voile_Self_tampered.jpg) | ![img](https://raw.githubusercontent.com/ninofiliu/perso/master/eurecom/media/voile_Self_tampered_checked.jpg) |

#### Comment the results

As we can see, the watermarked image present a good visibility. This is backed up by the fact that this self-embedding technique only modifies the LSB so the PSNR is comparable to the one of the CRC method and smaller or equal to the random LSB method. The restored zones are pixellated because the image is restored by 4x4 blocks. Note that the human eye is more sensible to resolution error than to color error, a more human-oriented way to upgrade this restoration techniaue could be to restore 2x2 blocks with four times less colour precision.

It is necessary to encode the restoration bits on a distant block because if someone tampers on a block, it is very likely that the LSB of this block will be tampered on too. Note that here we used a (ti=4,tj=4) vector to chose our distant block but it is not large enough to restore zones larger than 4 blocks: only the bottom right 32-pixels-wide border of a tampered zone gets restored, and that is given the fact that the distant block has not been tampered too. We can observe this on the picture: the large wave zone does not get completely recovered but the small one does.

In terms of reliability, an attacker who knows that this techniaue is being used can trick the algorithm into restoring a fake original zone by tampering on the last 32 bits of each 8x8 blocks.



## Compare the three methods

| . | **random LSB** | **CRC** | **self-embedding** |
| --- | --- | --- | --- |
| visibility | very good, only the LSB gets modified | very good, only half of the LSB gets modified | very good, only the LSB gets modified |
| reliability | bad, easy to trick by only tampering on the 7 MSB, so virtually any transformation can be done and it won't be detected | medium, in order to tamper without detection, some visibility has to be traded off | detection relies on CRC, so same as for the CRC method. Restoration can come handy but it can be tricked |
| complexity | easy: pixel by pixel, straight forward verification | medium: blocking adds complexity and CRC writing/checking | hard: same as for the CRC, plus some difficulty on writing on and reading the restoration bits |
| functionalities | pixel by pixel tampering detection | block by block tampering detection | block by block tampering detection + restoration |

A common drawback in all of these methods is that they are not reversible: once the watermarking is done, 1/8 (or 1/16 for the CRC) bits of information is lost. This is not a problem for most images, since if only the LSB is modified, it amounts for only 1/128 of the luminosity of a pixel, but for professional photography (where watermarking is useful) it can be a flaw.

Moreover, all of these algorithms relies on the assumption that the image is not compressed, because it bases detection and embedding on LSB manipulation. However lossless formats represent only a small fraction of all the image traffic on the web today.

The final drawback, which is the most crucial but at the sane time the most difficult to address, is that these algorithms detect as "tampering" almost everything - one bit per block or per pixel that is not as predicted, and tampering is detected. There is no way to make a difference between a legitimately tampered image (compression, contrast adjustment, small luminosity shift, etc) with illegitimate ones (photoshopping of objects, text change, etc).
