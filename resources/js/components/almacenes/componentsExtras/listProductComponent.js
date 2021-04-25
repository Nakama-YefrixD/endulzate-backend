
import React from 'react'
import {Component} from 'react';


class ListProductComponent extends Component {

    constructor(){
        super();
        this.state ={

            precio: '',
            cantidad:'',
            importe:''
        }
        this.handleChange = this.handleChange.bind(this);
    }

    handleChange (e){
        const {name , value} = e.target;
        this.setState({
            [name] : value

        })
        console.log(name);

    }


    render(){
        return(

            <div className="form-group">
              <div className="row">
                      <div className="col-2">
                         <label>Producto de entrada</label><br/>
                         <input type="text" name="productoEntradaChild" className="form-control" onKeyDown={this.props.handleKeyDown} onFocus={()=>this.props.handleScope(this.props.number)} autoFocus />
                        </div>
                        <div className="col-2">
                            <label>Nombre</label><br/>
                            <input type="text" name="productoEntrada" className="form-control" value={this.props.name}/>
                        </div>
                        <div className="col-2 precioCompraContainer">
                              <label>Precio de compra</label>
                              <input type="number" name="precioProductoChild" id="precio" className="form-control precioCompra"
                                 pattern="^\S/\d{1,3}(,\d{3})*(\.\d+)?$" value={this.props.precioProductoChild} data-type="currency" placeholder="S/" onChange={this.props.handleChange} onFocus={()=>this.props.handleScope(this.props.number)}/>
                        </div>
                        <div className="col-2 cantidadCompraContainer">
                              <label>Cantidad</label>
                              <input type="number" className="form-control cantidadProductoEntrada" name="cantidadProductoChild" id="cantidad" value={this.props.cantidadProductoChild} onChange={this.props.handleChange} onFocus={()=>this.props.handleScope(this.props.number)}/>
                        </div>
                        <div className="col-1">
                            <label>Stock</label>
                            <input type="text" className="form-control cantidadProductoEntrada" name="stock" value={this.props.stock} />
                        </div>
                        <div className="col-2 importeCompraContainer">
                                <label>Importe</label>
                                 <input type="text" name="importe[]" id="importe" className="form-control " pattern="^\S/\d{1,3}(,\d{3})*(\.\d+)?$" value={this.props.importeChild} data-type="currency" placeholder="S/1,000,000.00" onChange={this.handleChange}/>
                         </div>
                         <div className="col-1">
                                  <br/>
                                <button type="button" className="btn btn-danger btn-rounded btn-icon remove" onClick={()=>this.props.quitarOpcion(this.props.number)}><i className="mdi mdi-close"></i></button>
                         </div>

              </div>
        </div>
         ) }}

    export default ListProductComponent
