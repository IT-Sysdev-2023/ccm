import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import Components from "unplugin-vue-components/vite";
import {
    AntDesignVueResolver,
} from "unplugin-vue-components/resolvers";

export default defineConfig({
    plugins: [
        laravel({
            input: "resources/js/app.js",
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        Components({
            resolvers: [
				AntDesignVueResolver({  resolveIcons: true, importStyle: false }), 
				(componentName) => {
					if (['Head', 'Link'].includes(componentName)) {
						return { name: componentName, from: '@inertiajs/vue3' }
					}
				}
			],
			dirs: [
				'resources/js/Components',
				'resources/js/Layouts',
				'resources/js/Pages',
			]
        })
    ],
});
