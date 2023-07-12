import{k as _,q as U,s as f,r as p,o as b,h as V,j as y,a as m,d as l,e as t,i as C,I as F,c as P,F as j}from"./app.ca5d1c04.js";const k={class:"grid grid-cols-3 gap-6"},A={class:"col-span-3 sm:col-span-1"},H={class:"col-span-3 sm:col-span-1"},L={class:"col-span-3 sm:col-span-1"},O={class:"col-span-3 sm:col-span-1"},S={class:"col-span-3 sm:col-span-1"},T={name:"CompanyBranchForm"},E=Object.assign(T,{setup(B){const d=_(),s={name:"",code:"",alias:"",parent:"",description:""},u="company/branch/",r=U(u),n=f({...s}),i=f({parent:"",isLoaded:!d.params.uuid}),g=o=>{var e,c;Object.assign(s,{name:o.name,code:o.code,alias:o.alias,description:o.description,parent:(e=o.parent)==null?void 0:e.uuid}),Object.assign(n,F(s)),i.parent=(c=o.parent)==null?void 0:c.uuid,i.isLoaded=!0};return(o,e)=>{const c=p("BaseInput"),h=p("BaseSelectSearch"),$=p("BaseTextarea"),v=p("FormAction");return b(),V(v,{"init-url":u,"init-form":s,form:n,"set-form":g,redirect:"CompanyBranch"},{default:y(()=>[m("div",k,[m("div",A,[l(c,{type:"text",modelValue:n.name,"onUpdate:modelValue":e[0]||(e[0]=a=>n.name=a),name:"name",label:o.$trans("company.branch.props.name"),error:t(r).name,"onUpdate:error":e[1]||(e[1]=a=>t(r).name=a),autofocus:""},null,8,["modelValue","label","error"])]),m("div",H,[l(c,{type:"text",modelValue:n.code,"onUpdate:modelValue":e[2]||(e[2]=a=>n.code=a),name:"code",label:o.$trans("company.branch.props.code"),error:t(r).code,"onUpdate:error":e[3]||(e[3]=a=>t(r).code=a),autofocus:""},null,8,["modelValue","label","error"])]),m("div",L,[l(c,{type:"text",modelValue:n.alias,"onUpdate:modelValue":e[4]||(e[4]=a=>n.alias=a),name:"alias",label:o.$trans("company.branch.props.alias"),error:t(r).alias,"onUpdate:error":e[5]||(e[5]=a=>t(r).alias=a),autofocus:""},null,8,["modelValue","label","error"])]),m("div",O,[i.isLoaded?(b(),V(h,{key:0,name:"parent",label:o.$trans("global.select",{attribute:o.$trans("company.branch.props.parent")}),modelValue:n.parent,"onUpdate:modelValue":e[6]||(e[6]=a=>n.parent=a),error:t(r).parent,"onUpdate:error":e[7]||(e[7]=a=>t(r).parent=a),"init-search":i.parent,"search-action":"company/branch/list"},null,8,["label","modelValue","error","init-search"])):C("",!0)]),m("div",S,[l($,{modelValue:n.description,"onUpdate:modelValue":e[8]||(e[8]=a=>n.description=a),name:"description",label:o.$trans("company.branch.props.description"),error:t(r).description,"onUpdate:error":e[9]||(e[9]=a=>t(r).description=a)},null,8,["modelValue","label","error"])])])]),_:1},8,["form"])}}}),I={name:"CompanyBranchAction"},D=Object.assign(I,{setup(B){const d=_();return(s,u)=>{const r=p("PageHeaderAction"),n=p("PageHeader"),i=p("ParentTransition");return b(),P(j,null,[l(n,{title:s.$trans(t(d).meta.trans,{attribute:s.$trans(t(d).meta.label)}),navs:[{label:s.$trans("company.company"),path:"Company"},{label:s.$trans("company.branch.branch"),path:"CompanyBranchList"}]},{default:y(()=>[l(r,{name:"CompanyBranch",title:s.$trans("company.branch.branch"),actions:["list"]},null,8,["title"])]),_:1},8,["title","navs"]),l(i,{appear:"",visibility:!0},{default:y(()=>[l(E)]),_:1})],64)}}});export{D as default};
