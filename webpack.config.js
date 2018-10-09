const path = require('path');
const PATHS = {
    app: path.resolve(__dirname, 'src'),
    build: path.resolve(__dirname, 'public/build')
};
module.exports = {
    entry: {
        app: PATHS.app + "/index.js"
    },
    output: {
        path: PATHS.build,
        filename: 'bundle.js'
    },
    module: {
        rules: [
            {
                test: /\.css$/,
                use: ['style-loader','css-loader']
            }
        ]
    }
};