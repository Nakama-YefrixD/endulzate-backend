import React from 'react'
import {Component} from 'react';
import TB_CajasVentas from './tabla/tb_cajasVentas';

class indexCajaVenta extends Component {
    constructor(){
        super();
        this.state ={

        }

    }

    


    render(){
        return(
            <div className="row">
               <div className="col-lg-12 grid-margin stretch-card">
                    <div className="card">
                        <div className="card-body">
                            <TB_CajasVentas 
                                    
                            />
                        </div>   
                    </div>    
                </div>
            </div>

        )
    }
}

export default indexCajaVenta
