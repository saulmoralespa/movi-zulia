import React from 'react';
import ReactDOM from 'react-dom';
import TableDriversComponent from "./TableDriversComponent";

if (document.getElementById('table-drivers')) {
    ReactDOM.render( <TableDriversComponent />, document.getElementById('table-drivers'));
}
