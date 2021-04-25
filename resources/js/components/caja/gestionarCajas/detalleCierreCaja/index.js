import React from 'react'
import {Component} from 'react';
import TB_filas_cajasVentas from './tb_filas_cajasVentas';
import agregarBotones from '../../../extras/agregarBotones'

class TB_CajasVentas extends Component {
    
    constructor(){
        super();
        this.state ={
            tb_cajaVenta_detallada    : [],

        }
        this.fetchCajaVentaDetallada  = this.fetchCajaVentaDetallada.bind(this);
    }

    componentDidMount(){
        this.fetchCajaVentaDetallada(1, '', '');
    }

    fetchCajaVentaDetallada(number, cliente, numeroComprobante){
        let url = `/cierrescajas/tb_cajaVenta_detallada?page=${number}&bcliente=${cliente}&bnumeroComprobante=${numeroComprobante}`;
        fetch(
            url
        )
        .then(
            res => res.json()
        )
        .then(
            data => {

                this.setState({
                    tb_cajaVenta_detallada    : data['tb_cajaVenta_detallada'].data,
                    numeroPagina    : number
                },()=>{
                    
                    this.setState({
                        estadoBoton : agregarBotones(data['tb_cajaVenta_detallada'].last_page)
                    })
                });
            }
        )
    }

    



    render(){
        return(
            <div>
                <h6 className="card-title">CIERRE DE CAJA N°:</h6>
                <div className="row">
                    <div className="col-4">
                        <label> SUCURSAL: </label>
                        <h4 className="card-title"> SUCURSAL 2 </h4>
                    </div>
                    <div className="col-4">
                        <label> USUARIO: </label>
                        <h4 className="card-title"> SUCURSAL 2 </h4>
                    </div>
                    <div className="col-4">
                        <label> ESTADO: </label>
                        <h4 className="card-title"> SUCURSAL 2 </h4>
                    </div>
                </div>
                <div className="row">
                    <div className="col-4">
                        <label> FECHA Y HORA DE APERTURA: </label>
                        <h4 className="card-title"> SUCURSAL 2 </h4>
                    </div>
                    <div className="col-4">
                        <label> FECHA Y HORA DE CIERRE: </label>
                        <h4 className="card-title"> SUCURSAL 2 </h4>
                    </div>
                </div>
                <div className="row">
                    <div className="col-4">
                        <label> DINERO CON EL QUE DEBERIA APERTURAR: </label>
                        <h4 className="card-title"> SUCURSAL 2 </h4>
                    </div>
                    <div className="col-4">
                        <label> DINERO CON EL QUE APERTURO: </label>
                        <h4 className="card-title"> SUCURSAL 2 </h4>
                    </div>
                    <div className="col-4">
                        <label> DIFERENCIA: </label>
                        <h4 className="card-title"> SUCURSAL 2 </h4>
                    </div>
                </div>

                <div className="row">
                    <div className="col-4">
                        <label> DINERO CON EL QUE DEBERIA HABER CERRADO: </label>
                        <h4 className="card-title"> SUCURSAL 2 </h4>
                    </div>
                    <div className="col-4">
                        <label> DINERO CON EL QUE CERRO: </label>
                        <h4 className="card-title"> SUCURSAL 2 </h4>
                    </div>
                    <div className="col-4">
                        <label> DIFERENCIA: </label>
                        <h4 className="card-title"> SUCURSAL 2 </h4>
                    </div>
                </div>
                


            </div>
         ) 
    }
}

export default TB_CajasVentas