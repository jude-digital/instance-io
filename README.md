# InstanceIO (IIO) - Component Based PHP Framework
* This is the official IIO repository based off of the flat-file system by Alonzi, Joseph Alonzi;
  * The Alonzi/j.alonzi repository may or may not be supported in the future;
  * This repository includes V2 of the platform;
  * Inter Planetary File-System Ready;
  * Public Docker container to be made available (IPFS Ready);

# Requirements
* PHP7+
* Apache2+
* jq
  * install: apt-get install jq

# Future Support
* Nginx
* Go

# Build Instructions (Single Command)

## Build All At Once (Component Styles + Sitemap)
```python
sudo cd iio/app/console/; bash build.sh all;
```

## Build Only Component styles
```python
sudo cd iio/app/console/; bash build.sh;
```

# Build Instructions (Separate Commands)

## Parse Component Styles
```python
sudo cd iio/app/console/; bash run.sh parse-styles";
```

## Build Sitemap
```python
sudo cd iio/app/console/; bash run.sh build-app sync;
```
