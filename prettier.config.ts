import type { Config } from "prettier";

export default {
    parser: "php",
    plugins: ["@prettier/plugin-php"],
    phpVersion: "auto",
    printWidth: 80,
    tabWidth: 4,
    useTabs: false,
    singleQuote: false,
    trailingCommaPHP: true,
    braceStyle: "per-cs",
} satisfies Config;
