# Vue CLI

## Install it

1. `npm imstall -g @vue/cli`

## Create project

1. `vue create my-project`
1. Choose default for now

- Vue CLI way will allow you to setup less/sass support (webpack loaders), typescript, how you want your imports working, etc.
- If we had chosen manual/custom preset we would have vuex, vue router, pwa support etc
- webpack config abstracted away (hidden) but can tweak via vue.config.js
- Instant Prototyping; no need to setup a Vue project to develop single components
- Graphical User Interface - easy to create/manage projects, manage plugins/deps

## Run it

1. `cd my-project`
1. `npm run serve`

- It should show up in your browser

## Examine folder structure

1. See https://www.youtube.com/watch?v=D_z-RAweP1k#t=25m45s
1. View app.vue

- notice each .vue file we have html, css, js;
- style scoped means only apply styles to this component; ensures no collisions
- can drag/drop other components; all encapsulated
- notice there's hot reloading too

1. View components folder

1. If you inspect, it seems to generate one style per component
1. But if you build, it'll generate only 1 js, 1 css.
1. `li { font-size; 30px }`
1. `h3:hover { color: blue }`

- public folder; notice index.html has blank body
- src folder has assets folder for images
- main.js kick starts our app
- .gitignore tells git which files to ignore
- babel.config.js - can choose babel config preset; allows us to use next gen js features that not supported by all browsers.
- package.json - see deps, see cli-plugin-babel (see browserlist) and cli-plugin-eslint which refers to our default preset

1. talk about scripts in package.json; notice it's in JS object format

## Run lint

1. `npm run lint`

## Creating a custom preset

1. `vue create my-custom`
1. Choose Manually select features
1. Select: router (Y), vuex, css (sass) pre-processors, linter (on save, dedicated)

## Adding plugins

1. `vue add vuetify`

## Instant prototyping

1. `npm i -g @vue/cli-service-global`
1. `vue serve whatever.vue`

## GUI

1. `vue ui`
