import{k as B,q as V,s as C,r as a,o as y,c as F,d as o,e as n,j as c,F as U,a as i,v as P,x as $}from"./app.ca5d1c04.js";const k={class:"grid grid-cols-3 gap-6"},x={class:"col-span-3 sm:col-span-1"},E={class:"col-span-3 sm:col-span-1"},N={class:"mt-4"},T={name:"License"},H=Object.assign(T,{setup(j){const u=B(),m={accessCode:"",email:""},d="product/",r=V(d),s=C({...m}),_=()=>{location.reload()};return(l,e)=>{const f=a("PageHeader"),p=a("BaseInput"),g=a("BaseButton"),b=a("FormAction"),v=a("ParentTransition");return y(),F(U,null,[o(f,{title:l.$trans(n(u).meta.title),navs:[]},null,8,["title"]),o(v,{appear:"",visibility:!0},{default:c(()=>[o(b,{"no-action-button":"",action:"license","init-url":d,"init-form":m,form:s,"after-submit":_},{default:c(()=>[i("div",k,[i("div",x,[o(p,{type:"text",modelValue:s.accessCode,"onUpdate:modelValue":e[0]||(e[0]=t=>s.accessCode=t),name:"accessCode",label:l.$trans("setup.license.props.access_code"),error:n(r).accessCode,"onUpdate:error":e[1]||(e[1]=t=>n(r).accessCode=t)},null,8,["modelValue","label","error"])]),i("div",E,[o(p,{type:"text",modelValue:s.email,"onUpdate:modelValue":e[2]||(e[2]=t=>s.email=t),name:"email",label:l.$trans("setup.license.props.registered_email"),error:n(r).email,"onUpdate:error":e[3]||(e[3]=t=>n(r).email=t)},null,8,["modelValue","label","error"])])]),i("div",N,[o(g,{design:"primary",type:"submit"},{default:c(()=>[P($(l.$trans("general.proceed")),1)]),_:1})])]),_:1},8,["form"])]),_:1})],64)}}});export{H as default};