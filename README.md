# Krypton

An evolution of the great [Argon template](https://github.com/IceWreck/Argon-Dokuwiki-Template). Clean, responsive, modern. Inspired by [Argon-Alt](https://github.com/jlysd/Argon-Dokuwiki-Template). 

A part of the [dokuwiki-preconfigured](https://github.com/fablab-luenen/dokuwiki-preconfigured/) project. 

## Goals / Improvements over Argon

- Sticky/docking page buttons for long pages
- More configurability
- Edit button at the top of the page
- Various small fixes (smooth anchor scrolling, word wrap)

![Screenshot](screenshots/1.png)

## Development
"I've imported the base stylesheet from the argon design system and then added custom styles on top in the assets/css/doku.scss file. The file is then compiled to CSS using SASS."

Hint: This uses the new Dart Sass (`npm install -g`/`npx`), not Ruby Sass (`gem install`). 

```
npx sass --watch assets/css/doku.scss assets/css/doku.css
```

## Contributors

- [IceWreck](https://github.com/IceWreck) (Argon)
- [SoarinFerret](https://github.com/SoarinFerret) (Argon)
- [llune](https://github.com/llune) (Argon)
- [solarkraft](https://github.com/solarkraft) (Krypton)
