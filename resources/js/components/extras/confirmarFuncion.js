import React from 'react'
import { confirmAlert } from 'react-confirm-alert'; // Import
import 'react-confirm-alert/src/react-confirm-alert.css'; // Import css

export default function(titulo, descripcion, funcion)
{

    confirmAlert({
        customUI: ({ onClose }) => {
            return (
                <div className="page-content container-fluid">
                    <div className="row">
                        <div className="col-12">
                            <div className='card'>
                                <div className="card-body">
                                    <h1>{titulo}</h1>
                                    <p>{descripcion}</p>
                                    <div className="form-group boton">
                                        <div className="row">
                                            <div className="col-6">
                                                <button 
                                                    type="button"
                                                    onClick={() => {
                                                        funcion();
                                                        onClose();
                                                    }}
                                                    className="btn waves-effect waves-light btn-block btn-info">
                                                        ACEPTAR
                                                </button>
                                            </div>
                                            <div className="col-6">
                                                <button 
                                                    type="button" 
                                                    onClick={() => {
                                                        onClose();
                                                    }}
                                                    className="btn waves-effect waves-light btn-block btn-danger">
                                                        CANCELAR
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            );
        }
    });

} 
