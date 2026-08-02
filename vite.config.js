import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import fs from 'fs';
import path from 'path';

function manifestPlugin() {
    let outDir;
    return {
        name: 'manifest',
        configResolved(config) { outDir = config.build.outDir; },
        writeBundle() {
            const assets = {};
            const dir = path.resolve(outDir, 'assets');
            if (fs.existsSync(dir)) {
                for (const file of fs.readdirSync(dir)) {
                    if (file.endsWith('.css')) assets['resources/css/app.css'] = `/build/assets/${file}`;
                }
                for (const file of fs.readdirSync(dir)) {
                    if (file.endsWith('.js') && file.startsWith('app-')) assets['resources/js/app.js'] = `/build/assets/${file}`;
                }
            }
            fs.writeFileSync(path.resolve(outDir, 'mix-manifest.json'), JSON.stringify(assets, null, 2));
        },
    };
}

export default defineConfig({
    plugins: [vue(), manifestPlugin()],
    publicDir: 'public',
    build: {
        outDir: 'public/build',
        emptyOutDir: true,
        rollupOptions: {
            input: ['resources/js/app.js', 'resources/css/app.css'],
            output: {
                entryFileNames: 'assets/app-[hash].js',
                chunkFileNames: 'assets/[name]-[hash].js',
                assetFileNames: 'assets/[name]-[hash].[ext]',
            },
        },
    },
    resolve: {
        alias: { '@': '/resources/js' },
    },
});
