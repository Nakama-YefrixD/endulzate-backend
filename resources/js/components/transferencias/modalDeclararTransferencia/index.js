import React, { Component } from 'react'
import cogoToast from 'cogo-toast';

class ModalDeclararTransferencia extends Component {
    constructor(){
        super();
        this.state ={

            codigoProductoEscaneado     : '',
            idProductoEscaneado         : 0,
            nombreProductoEscaneado     : '',
            stockProductoEscaneado      : 0,
            precioProductoEscaneado     : 0,

            idOrigen                    : '',
            idDestino                   : '',
            cantidadTransferir          : '',
            motivoTransferencia         : '',
            stockDespuesOrigen          : '',
            

        }

        
        this.getCambioCodigo        = this.getCambioCodigo.bind(this);
        this.fetchCodigoProducto    = this.fetchCodigoProducto.bind(this);

        this.getCambioCantidadTransferir    = this.getCambioCantidadTransferir.bind(this);
        this.getCambioMotivoTransferencia   = this.getCambioMotivoTransferencia.bind(this);
        this.getCambio                      = this.getCambio.bind(this);
        this.fetchCrearTransferencia        = this.fetchCrearTransferencia.bind(this);
        

    }    

    componentDidMount(){
        this.setState({
            idOrigen    : this.props.sucursalesUsuario[0]['id'],
            idDestino   : this.props.sucursales[0]['id']
        })
    }

    getCambioCodigo(e)
    {
        const {name , value} = e.target;
        this.setState({
            codigoProductoEscaneado : value
        })
        if (e.key === 'Enter') {
            this.fetchCodigoProducto(value);
        }

    }

    getCambioCantidadTransferir(e)
    {
        const {name , value} = e.target;
        this.setState({
            cantidadTransferir : value
        })
    }
    
    fetchCodigoProducto(codigo){
        cogoToast.loading(
            <div>
                <h4>BUSCANDO PRODUCTO</h4>
            </div>, 
            {
                position: 'top-right'
            }
            
        ).then(() => {
            fetch(`/producto/buscar`,
                {
                    method: 'POST',
                    body: JSON.stringify({
                        '_token'        : csrf_token,
                        codigoProducto  : codigo
                    }),
                    headers: {
                        'Accept' : 'application/json',
                        'Content-Type': 'application/json',
                    }
                }
            )
            .then(response => response.json())
            .then(data => {
                if(data['response'] == true){
                    
                    var producto = data['producto'];
                    this.setState({
                        idProductoEscaneado     : producto['id'],
                        nombreProductoEscaneado : producto['nombre'],
                        stockProductoEscaneado  : producto['cantidad'],
                        precioProductoEscaneado : producto['precio'],
                    })

                    cogoToast.success(
                        <div>
                            <h4>PRODUCTO ESCANEADO CORRECTAMENTE</h4>
                            <p>{producto['nombre']}</p>
                        </div>, 
                        {
                          position: 'top-right'
                        }
                    );


                }else{
                    cogoToast.error(
                        <div>
                            <h4>EL CODIGO DE ESE PRODUCTO NO EXISTE</h4>
                        </div>, 
                        {
                          position: 'top-right'
                        }
                    );
                }
                
            })
        })
        
    }

    getCambioMotivoTransferencia(e){
        const {name , value} = e.target;
        
        this.setState({
            motivoTransferencia  : value
        })
    }

    fetchCrearTransferencia(){
        this.props.mostrarModalDeclararTransferencia()

        cogoToast.loading(
            <div>
                <h4>TRANSFIRIENDO PRODUCTO</h4>
            </div>, 
            {
                position: 'top-right'
            }
            
        ).then(() => {
            fetch(`/almacen/transferencias/crear`,
                {
                    method: 'POST',
                    body: JSON.stringify({
                        '_token'                : csrf_token,
                        'idOrigen'              : this.state.idOrigen,
                        'idDestino'             : this.state.idDestino,
                        'idProducto'            : this.state.idProductoEscaneado,
                        'codigoProducto'        : this.state.codigoProductoEscaneado,
                        'cantidad'              : this.state.cantidadTransferir,
                        'motivo'                : this.state.motivoTransferencia,
                        'stockAntesOrigen'      : this.state.stockProductoEscaneado,
                        'stockDespuesOrigen'    : this.state.stockProductoEscaneado + this.state.cantidadTransferir
                    }),
                    headers: {
                        'Accept' : 'application/json',
                        'Content-Type': 'application/json',
                    }
                }
            )
            .then(response => response.json())
            .then(data => {
                if(data['respuesta'] == true){
                    
                    this.props.fetchSucursalesUsuario
                    this.props.fetchSucursales

                    cogoToast.success(
                        <div>
                            <h4> {data['mensaje']}   </h4>
                        </div>, 
                        {
                          position: 'top-right'
                        }
                    );


                }else{
                    cogoToast.error(
                        <div>
                            <h4>{data['mensaje']}</h4>
                        </div>, 
                        {
                          position: 'top-right'
                        }
                    );
                }
                
            })
        })
        
    }

    getCambio (e){
        const {name , value} = e.target;
        this.setState({
            [name] : value

        })
    }

    render() {
        return (
            <div className="card card-default">
               <div className="modal-body">
                   <div className="card-body">
                        <div className="form-group">
                            <div className="row">
                                <div className="col-6">
                                    <label>ORIGEN</label>
                                    <div className="input-group">
                                           <select 
                                            className   = "form-control"
                                            name        = "idOrigen"
                                            onChange    = {this.getCambio}
                                            >
                                               {
                                                   this.props.sucursalesUsuario 
                                                   ?this.props.sucursalesUsuario.map(element => {
                                                       return (
                                                           <option key = {element.id} value={element.id}  >{element.nombre}</option>
                                                           );
                                                   })
                                                   :null
                                               }
                                           </select>
                                       </div>
                                </div>
                                <div className="col-6">
                                    <label>DESTINO</label>
                                    <div className="input-group">
                                        <select 
                                            className   = "form-control" 
                                            name        = "idDestino"
                                            onChange    = {this.getCambio}
                                        >
                                            {
                                                this.props.sucursales 
                                                ?this.props.sucursales.map(element => {
                                                    return (
                                                        <option key = {element.id} value={element.id}  >{element.nombre}</option>
                                                        );
                                                })
                                                :null
                                            }
                                            
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div className="form-group" >
                                <div className="row">
                                    <div className="col-9">
                                        <label>Codigo</label>
                                        <input 
                                            type        = "text" 
                                            className   = "form-control" 
                                            name        = "codigoProductoEscaneado" 
                                            id          = "codigoProductoEscaneado"
                                            onChange    = {this.getCambioCodigo}
                                            onKeyDown   = {this.getCambioCodigo}
                                            autoFocus
                                            value       = {this.state.codigoProductoEscaneado} 
                                        />
                                    </div>
                                    <div className="col-3">
                                        <label>Cantidad</label>
                                        <input 
                                            type        = "text" 
                                            className   = "form-control" 
                                            onChange    = {this.getCambioCantidadTransferir}
                                            value       = {this.state.cantidadTransferir} 
                                            
                                        />
                                    </div>
                                    
                                </div>
                            </div>
                            <div className="form-group">
                                <div className="row">
                                    <div className="col-7">
                                        <label>Nombre del producto</label>
                                        <h4 className="card-title"> {this.state.nombreProductoEscaneado} </h4>
                                    </div>
                                    <div className="col-3">
                                        <label>Stock</label>
                                        <h4 className="card-title"> 
                                            {this.state.stockProductoEscaneado} - {this.state.cantidadTransferir} = {this.state.stockProductoEscaneado - this.state.cantidadTransferir}
                                        </h4>
                                    </div>
                                    <div className="col-2">
                                        <label>Precio</label>
                                        <h4 className="card-title"> S/{this.state.precioProductoEscaneado} </h4>
                                    </div>
                                </div>
                            </div>
                            <div className="form-group">
                                <div className="row">
                                    <div className="col-12">
                                        <label>MOTIVO (opcional)</label>
                                        <textarea 
                                            className   = "form-control" 
                                            rows        = "3" 
                                            placeholder = "Motivo"
                                            onChange    = {this.getCambioMotivoTransferencia}
                                            value       = {this.state.motivoTransferencia}
                                        >

                                        </textarea>
                                    </div>                                        
                                </div>
                            </div>
                        </div>
                        <div className="form-group boton">
                            <button 
                            type="button" 
                            className="addexis form-control btn btn-block btn-success btn-lg" 
                            onClick={() => this.fetchCrearTransferencia()}
                            >
                            TRANSFERIR</button>
                        </div>
                    </div>
               </div>
           </div>
        );
    }
}

export default ModalDeclararTransferencia