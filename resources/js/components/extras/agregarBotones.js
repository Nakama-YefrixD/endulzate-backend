import React, { Component } from 'react'

export default function(lastPage)
{
    let z = lastPage;
    let x = [];
    if(z){
        for(let i = 1 ; i <= z ; i++){
            x.push(i);
        }
        return x;
    }    

} 
