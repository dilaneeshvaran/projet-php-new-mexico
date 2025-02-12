const esbuild = require("esbuild");
const sassPlugin = require("esbuild-plugin-sass");

async function build() {
  await esbuild.build({
    entryPoints: ["src/assets/js/main.js"],
    bundle: true,
    outfile: "public/assets/main.js",
    plugins: [sassPlugin()],
    sourcemap: false,
    minify: true,
  });

  console.log("Build completed for production.");
}

build().catch(() => process.exit(1));
