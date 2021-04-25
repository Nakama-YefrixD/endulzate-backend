import React, { Component } from 'react'
const modalContext = React.createContext({
    estadoModal: ''

}); // Create a context object

export {
  modalContext // Export it so it can be used by other Components
};