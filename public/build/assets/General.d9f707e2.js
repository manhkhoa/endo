import{k as w,A as U,q as A,b as S,s as v,r as n,o as D,c as F,d as r,e as o,j as d,F as M,a as i,v as g,x as _}from"./app.ca5d1c04.js";const R={class:"grid grid-cols-3 gap-4"},j={class:"col-span-3 sm:col-span-1"},q={class:"col-span-3 sm:col-span-1"},C={class:"col-span-3 sm:col-span-1"},E={class:"col-span-3"},G={class:"mt-4 grid grid-cols-3 gap-4"},I={class:"col-span-3 sm:col-span-1"},H={class:"mt-4 grid grid-cols-2 gap-4"},O={class:"col-span-2 sm:col-span-1"},$={class:"col-span-2 sm:col-span-1"},z={name:"TaskConfigGeneral"},K=Object.assign(z,{setup(h){const T=w(),l=U("$trans"),p="config/",t=A(p),x=S(()=>l("global.placeholder_info",{attribute:u.datePlaceholders})),u=v({datePlaceholders:""}),m={codeNumberPrefix:"",codeNumberDigit:"",codeNumberSuffix:"",view:"",isAccessibleToTopLevel:!1,isManageableByTopLevel:!1,type:"task"},a=v({...m}),N=b=>{Object.assign(u,{datePlaceholders:b.datePlaceholders.map(e=>e.value).join(", ")})};return(b,e)=>{const V=n("PageHeader"),c=n("BaseInput"),k=n("BaseAlert"),B=n("BaseLabel"),P=n("BaseRadioGroup"),f=n("BaseSwitch"),y=n("FormAction"),L=n("ParentTransition");return D(),F(M,null,[r(V,{title:o(l)(o(T).meta.label),navs:[{label:o(l)("task.task"),path:"Task"}]},null,8,["title","navs"]),r(L,{appear:"",visibility:!0},{default:d(()=>[r(y,{"pre-requisites":{data:["datePlaceholders"]},onSetPreRequisites:N,"init-url":p,"data-fetch":"task",action:"store","init-form":m,form:a,"stay-on":"",redirect:"Task"},{default:d(()=>[i("div",R,[i("div",j,[r(c,{type:"text",modelValue:a.codeNumberPrefix,"onUpdate:modelValue":e[0]||(e[0]=s=>a.codeNumberPrefix=s),name:"codeNumberPrefix",label:o(l)("task.config.props.number_prefix"),error:o(t).codeNumberPrefix,"onUpdate:error":e[1]||(e[1]=s=>o(t).codeNumberPrefix=s)},null,8,["modelValue","label","error"])]),i("div",q,[r(c,{type:"number",modelValue:a.codeNumberDigit,"onUpdate:modelValue":e[2]||(e[2]=s=>a.codeNumberDigit=s),name:"codeNumberDigit",label:o(l)("task.config.props.number_digit"),error:o(t).codeNumberDigit,"onUpdate:error":e[3]||(e[3]=s=>o(t).codeNumberDigit=s)},null,8,["modelValue","label","error"])]),i("div",C,[r(c,{type:"text",modelValue:a.codeNumberSuffix,"onUpdate:modelValue":e[4]||(e[4]=s=>a.codeNumberSuffix=s),name:"codeNumberSuffix",label:o(l)("task.config.props.number_suffix"),error:o(t).codeNumberSuffix,"onUpdate:error":e[5]||(e[5]=s=>o(t).codeNumberSuffix=s)},null,8,["modelValue","label","error"])]),i("div",E,[r(k,{design:"info"},{default:d(()=>[g(_(o(x)),1)]),_:1})])]),i("div",G,[i("div",I,[r(B,null,{default:d(()=>[g(_(o(l)("task.config.props.view")),1)]),_:1}),r(P,{"top-margin":"",options:[{label:o(l)("task.config.views.list"),value:"list"},{label:o(l)("task.config.views.card"),value:"card"},{label:o(l)("task.config.views.board"),value:"board"}],name:"view",modelValue:a.view,"onUpdate:modelValue":e[6]||(e[6]=s=>a.view=s),error:o(t).view,"onUpdate:error":e[7]||(e[7]=s=>o(t).view=s),horizontal:""},null,8,["options","modelValue","error"])])]),i("div",H,[i("div",O,[r(f,{vertical:"",modelValue:a.isAccessibleToTopLevel,"onUpdate:modelValue":e[8]||(e[8]=s=>a.isAccessibleToTopLevel=s),name:"isAccessibleToTopLevel",label:o(l)("task.config.props.is_accessible_to_top_level"),error:o(t).isAccessibleToTopLevel,"onUpdate:error":e[9]||(e[9]=s=>o(t).isAccessibleToTopLevel=s)},null,8,["modelValue","label","error"])]),i("div",$,[r(f,{vertical:"",modelValue:a.isManageableByTopLevel,"onUpdate:modelValue":e[10]||(e[10]=s=>a.isManageableByTopLevel=s),name:"isManageableByTopLevel",label:o(l)("task.config.props.is_manageable_by_top_level"),error:o(t).isManageableByTopLevel,"onUpdate:error":e[11]||(e[11]=s=>o(t).isManageableByTopLevel=s)},null,8,["modelValue","label","error"])])])]),_:1},8,["form"])]),_:1})],64)}}});export{K as default};
