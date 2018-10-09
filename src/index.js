import React from "react";
import ReactDOM from "react-dom";

require('./main.css')

const Index = () => {
    return <div>Hello Lumen & React!</div>;
};

ReactDOM.render(<Index />, document.getElementById("app"));