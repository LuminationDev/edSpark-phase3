module.exports = {
    extends: [
        // add more generic rulesets here, such as:
        'plugin:vue/vue3-recommended',
    ],
    rules: {
        // override/add rules settings here, such as:
        "vue/script-indent": ["error", 4, { "baseIndent": 0}],
        "vue/html-indent": ["error", 4, {
            "attribute": 1,
            "baseIndent": 1,
            "closeBracket": 0,
            "alignAttributesVertically": false,
            "ignores": []
        }]
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
