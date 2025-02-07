const esbuild = require("esbuild");
const sassPlugin = require("esbuild-plugin-sass");

async function build() {
  const ctx = await esbuild.context({
    entryPoints: ["src/assets/js/main.js"],
    bundle: true,
    outfile: "public/assets/main.js",
    plugins: [sassPlugin()],
    sourcemap: true,
  });

  await ctx.watch(); //watch mode for js
  console.log("Watching for changes...");
}

build().catch(() => process.exit(1));
