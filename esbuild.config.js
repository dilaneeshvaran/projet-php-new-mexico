const esbuild = require("esbuild");
const sassPlugin = require("esbuild-plugin-sass");

esbuild
  .build({
    entryPoints: ["src/assets/js/main.js"],
    bundle: true,
    outfile: "public/assets/main.js",
    plugins: [sassPlugin()],
    sourcemap: true,
    //format: "esm",
    //globalName: "NewMexicoFramework",
  })
  .catch(() => process.exit(1));
