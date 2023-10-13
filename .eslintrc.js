module.exports = {
    "plugins": ["@kalimahapps/eslint-plugin-tailwind","simple-import-sort"],
    extends: [
        // add more generic rulesets here, such as:
        'plugin:vue/vue3-recommended',
        '@vue/eslint-config-typescript/recommended',
    ],
    rules: {
        // override/add rules settings here, such as:
        "vue/script-indent": ["error", 4, { "baseIndent": 0}],
        "vue/html-indent": ["error", 4, {
            "attribute": 1,
            "baseIndent": 1,
            "closeBracket": 0,
            "alignAttributesVertically": true,
            "ignores": []
        }],
        "@kalimahapps/tailwind/sort": "warn",
        "@kalimahapps/tailwind/multiline": [
            "warn",
            {
                "maxLen": 150,
                "quotesOnNewLine": true
            }
        ],
        '@typescript-eslint/ban-ts-comment': ['warn', {'ts-ignore': 'allow-with-description'}],
        '@typescript-eslint/explicit-function-return-type': 'error',
        '@typescript-eslint/explicit-module-boundary-types': 'off',
        '@typescript-eslint/no-empty-function': ['warn', {allow: ['arrowFunctions']}],
        '@typescript-eslint/no-explicit-any': 'off',
        '@typescript-eslint/no-non-null-assertion': 'warn',
        '@typescript-eslint/no-var-requires': 'warn',
        "simple-import-sort/imports": "warn"
    },
    parserOptions: {
        ecmaVersion: 'latest',
        sourceType: 'module'
    },
    "overrides": [
        {
            "files": ["*.vue"],
            "rules": {
                "indent": "off"
            }
        }
    ]
}
