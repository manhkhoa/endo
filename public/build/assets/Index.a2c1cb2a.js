import{u as A,m as D,s as I,y as $,b as U,t as h,r as f,o as m,h as S,j as p,a as r,d as n,e as t,c as b,x as g,i as C,z as P,F as w,v as W}from"./app.ca5d1c04.js";const j={class:"w-full bg-secondary py-4 px-8 rounded-lg"},M={key:0,class:"text-xl text-primary text-center"},G={key:1,class:"text-dark-primary text-center"},J={class:"mb-4 text-sm font-medium"},K={class:"grid grid-cols-6 gap-6 mb-4"},O={class:"col-span-6 sm:col-span-2"},Q={class:"grid grid-cols-6 gap-6"},X={class:"col-span-6 sm:col-span-3"},Y={class:"col-span-6 sm:col-span-3"},Z={class:"col-span-6 sm:col-span-2"},ee={class:"col-span-6 sm:col-span-2"},se={class:"col-span-6 sm:col-span-2"},te={class:"grid grid-cols-6 gap-6"},ae={class:"col-span-6 sm:col-span-3"},oe={class:"col-span-6 sm:col-span-3"},le={class:"col-span-6 sm:col-span-2"},re={class:"col-span-6 sm:col-span-2"},ne={class:"col-span-6 sm:col-span-2"},de={class:"grid grid-cols-6 gap-6"},ie={class:"col-span-6 sm:col-span-3"},pe={class:"col-span-6 sm:col-span-3"},ce={__name:"Index",setup(ue){const u=A(),B=D(),o=I({dbHost:"localhost",dbPort:"3306",dbName:"",dbUsername:"",dbPassword:"",name:"",email:"",username:"",password:"",passwordConfirmation:"",accessCode:"",registeredEmail:""}),i=$(null),L=U(()=>u.getters["setup/install/getPreRequisites"]),c=U(()=>u.getters["setup/install/getApp"]),l=U(()=>u.getters["setup/install/getFormErrors"]);$(null),h(async()=>{i.value=!0,await u.dispatch("setup/install/fetchPreRequisite",{}).then(a=>{i.value=!1}).catch(a=>{i.value=!1})});const N=()=>{},E=()=>{},_=()=>{},k=()=>u.getters["setup/install/hasValidPreRequisite"],F=()=>V("db"),x=()=>V("user"),H=()=>V("license"),V=a=>(i.value=!0,u.dispatch("setup/install/validate",{option:a,form:o}).then(e=>(i.value=!1,!0)).catch(e=>(i.value=!1,!1))),T=async()=>{i.value=!0,await u.dispatch("setup/install/finish",{form:o}).then(()=>{i.value=!1,B.push({name:"Login"})}).catch(a=>{i.value=!1})};return(a,e)=>{const q=f("BaseAlert"),v=f("TabContent"),d=f("BaseInput"),R=f("FormWizard"),z=f("BaseLoader");return m(),S(z,{"is-loading":i.value},{default:p(()=>[r("div",j,[n(R,{onComplete:T,"next-button-text":a.$trans("setup.install.next"),"previous-button-text":a.$trans("setup.install.previous"),"finish-button-text":a.$trans("setup.install.finish")},{header:p(()=>[t(c).title?(m(),b("p",M,g(t(c).title+" "+t(c).version),1)):C("",!0),t(c).subtitle?(m(),b("p",G,g(t(c).subtitle),1)):C("",!0)]),default:p(()=>[n(v,{title:a.$trans("setup.install.step",{attribute:1}),description:a.$trans("setup.install.prerequisite_check"),"before-change":k},{default:p(()=>[(m(!0),b(w,null,P(t(L),s=>(m(),b(w,null,[r("h6",J,g(s.title),1),r("div",K,[(m(!0),b(w,null,P(s.items,y=>(m(),b("div",O,[n(q,{design:y.type},{default:p(()=>[W(g(y.message),1)]),_:2},1032,["design"])]))),256))])],64))),256))]),_:1},8,["title","description"]),n(v,{title:a.$trans("setup.install.step",{attribute:2}),description:a.$trans("setup.install.database_setup"),"before-change":F,"after-load":N},{default:p(()=>[r("div",Q,[r("div",X,[n(d,{type:"text",modelValue:o.dbHost,"onUpdate:modelValue":e[0]||(e[0]=s=>o.dbHost=s),name:"dbHost",label:a.$trans("setup.install.props.db_host"),error:t(l).dbHost,"onUpdate:error":e[1]||(e[1]=s=>t(l).dbHost=s),autofocus:""},null,8,["modelValue","label","error"])]),r("div",Y,[n(d,{type:"number",modelValue:o.dbPort,"onUpdate:modelValue":e[2]||(e[2]=s=>o.dbPort=s),name:"dbPort",label:a.$trans("setup.install.props.db_port"),error:t(l).dbPort,"onUpdate:error":e[3]||(e[3]=s=>t(l).dbPort=s)},null,8,["modelValue","label","error"])]),r("div",Z,[n(d,{type:"text",modelValue:o.dbName,"onUpdate:modelValue":e[4]||(e[4]=s=>o.dbName=s),name:"dbName",label:a.$trans("setup.install.props.db_name"),error:t(l).dbName,"onUpdate:error":e[5]||(e[5]=s=>t(l).dbName=s)},null,8,["modelValue","label","error"])]),r("div",ee,[n(d,{type:"text",modelValue:o.dbUsername,"onUpdate:modelValue":e[6]||(e[6]=s=>o.dbUsername=s),name:"dbUsername",label:a.$trans("setup.install.props.db_username"),error:t(l).dbUsername,"onUpdate:error":e[7]||(e[7]=s=>t(l).dbUsername=s)},null,8,["modelValue","label","error"])]),r("div",se,[n(d,{type:"password",modelValue:o.dbPassword,"onUpdate:modelValue":e[8]||(e[8]=s=>o.dbPassword=s),name:"dbPassword",label:a.$trans("setup.install.props.db_password"),error:t(l).dbPassword,"onUpdate:error":e[9]||(e[9]=s=>t(l).dbPassword=s)},null,8,["modelValue","label","error"])])])]),_:1},8,["title","description"]),n(v,{title:a.$trans("setup.install.step",{attribute:3}),description:a.$trans("setup.install.account_setup"),"before-change":x,"after-load":E},{default:p(()=>[r("div",te,[r("div",ae,[n(d,{type:"text",modelValue:o.name,"onUpdate:modelValue":e[10]||(e[10]=s=>o.name=s),name:"name",label:a.$trans("setup.install.props.name"),error:t(l).name,"onUpdate:error":e[11]||(e[11]=s=>t(l).name=s)},null,8,["modelValue","label","error"])]),r("div",oe,[n(d,{type:"email",modelValue:o.email,"onUpdate:modelValue":e[12]||(e[12]=s=>o.email=s),name:"email",label:a.$trans("setup.install.props.email"),error:t(l).email,"onUpdate:error":e[13]||(e[13]=s=>t(l).email=s),autofocus:""},null,8,["modelValue","label","error"])]),r("div",le,[n(d,{type:"text",modelValue:o.username,"onUpdate:modelValue":e[14]||(e[14]=s=>o.username=s),name:"username",label:a.$trans("setup.install.props.username"),error:t(l).username,"onUpdate:error":e[15]||(e[15]=s=>t(l).username=s)},null,8,["modelValue","label","error"])]),r("div",re,[n(d,{type:"password",modelValue:o.password,"onUpdate:modelValue":e[16]||(e[16]=s=>o.password=s),name:"password",label:a.$trans("setup.install.props.password"),error:t(l).password,"onUpdate:error":e[17]||(e[17]=s=>t(l).password=s)},null,8,["modelValue","label","error"])]),r("div",ne,[n(d,{type:"password",modelValue:o.passwordConfirmation,"onUpdate:modelValue":e[18]||(e[18]=s=>o.passwordConfirmation=s),name:"passwordConfirmation",label:a.$trans("setup.install.props.password_confirmation"),error:t(l).passwordConfirmation,"onUpdate:error":e[19]||(e[19]=s=>t(l).passwordConfirmation=s)},null,8,["modelValue","label","error"])])])]),_:1},8,["title","description"]),n(v,{title:a.$trans("setup.install.step",{attribute:4}),description:a.$trans("setup.install.license_validation"),"before-change":H,"after-load":_},{default:p(()=>[r("div",de,[r("div",ie,[n(d,{type:"text",modelValue:o.accessCode,"onUpdate:modelValue":e[20]||(e[20]=s=>o.accessCode=s),name:"accessCode",label:a.$trans("setup.license.props.access_code"),error:t(l).accessCode,"onUpdate:error":e[21]||(e[21]=s=>t(l).accessCode=s)},null,8,["modelValue","label","error"])]),r("div",pe,[n(d,{type:"email",modelValue:o.registeredEmail,"onUpdate:modelValue":e[22]||(e[22]=s=>o.registeredEmail=s),name:"registeredEmail",label:a.$trans("setup.license.props.registered_email"),error:t(l).registeredEmail,"onUpdate:error":e[23]||(e[23]=s=>t(l).registeredEmail=s),autofocus:""},null,8,["modelValue","label","error"])])])]),_:1},8,["title","description"])]),_:1},8,["next-button-text","previous-button-text","finish-button-text"])])]),_:1},8,["is-loading"])}}};export{ce as default};
