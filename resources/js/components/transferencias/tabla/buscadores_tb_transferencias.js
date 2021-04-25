import React from 'react'
import {Component} from 'react';

class Buscadores_tb_transferencias extends Component {
    
    constructor(){
        super();
        this.state ={



        }

    }

    componentDidMount(){        

    }

    render(){
        return(
            <div className="col-lg-12 grid-margin stretch-card">
                <div className="card">
                    <div className="card-body">
                        <h4 className="card-title">Buscar</h4>
                        <div className="row">
                            <div className="col-3">
                                <label>Buscar Codigo</label>
                                <input 
                                    type="text" 
                                    className="form-control form-control-lg" 
                                />
                            </div>
                            <div className="col-3">
                                <label>Buscar Marca</label>
                                <input 
                                    type="text" 
                                    className="form-control form-control-lg" 
                                />
                            </div>
                            <div className="col-3">
                                <label>Buscar Tipo</label>
                                <input 
                                    type="text" 
                                    className="form-control form-control-lg" 
                                />
                            </div>
                            <div className="col-3">
                                <label>Buscar Nombre</label>
                                <input 
                                    type="text" 
                                    className="form-control form-control-lg" 
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
         ) 
    }
}

export default Buscadores_tb_transferencias