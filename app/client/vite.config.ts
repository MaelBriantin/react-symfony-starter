import { defineConfig } from "vite";
import react from "@vitejs/plugin-react";
import tailwindcss from '@tailwindcss/vite'
import deno from "@deno/vite-plugin";

import "react";
import "react-dom";

export default defineConfig({
  root: "./",
  server: {
    port: 5173,
  },
  plugins: [
    deno(),
    react(),
    tailwindcss(),
  ],
  optimizeDeps: {
    include: ["react/jsx-runtime"],
  },
});
