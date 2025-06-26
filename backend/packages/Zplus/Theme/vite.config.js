import { defineConfig, loadEnv } from "vite";
import vue from "@vitejs/plugin-vue";
import laravel from "laravel-vite-plugin";
import path from "path";

export default defineConfig(({ mode }) => {
    const envDir = "../../../";

    Object.assign(process.env, loadEnv(mode, envDir));

    return {
        build: {
            emptyOutDir: true,
        },

        envDir,

        server: {
            host: process.env.VITE_HOST || "localhost",
            port: process.env.VITE_PORT || 5174,
            cors: true,
        },

        plugins: [
            vue(),

            laravel({
                hotFile: "../../../public/shop-zplus-vite.hot",
                publicDirectory: "../../../public",
                buildDirectory: "themes/shop/zplus/build",
                input: [
                    "src/Resources/assets/css/main.css",
                    "src/Resources/assets/css/mobile.css",
                ],
                refresh: true,
            }),
        ],

        experimental: {
            renderBuiltUrl(filename, { hostId, hostType, type }) {
                if (hostType === "css") {
                    return path.basename(filename);
                }
            },
        },
    };
});