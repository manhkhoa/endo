import{k as U,s as V,q,r as l,o as L,h as v,j as T,a as i,d,e as o,i as $,I as H,c as j,F as A}from"./app.ca5d1c04.js";const O={class:"grid grid-cols-3 gap-6"},D={class:"col-span-3 sm:col-span-1"},R={class:"col-span-3 sm:col-span-1"},h={class:"mt-4 grid grid-cols-3 gap-6"},C={class:"col-span-3 sm:col-span-1"},E={class:"col-span-2 sm:col-span-1"},I={class:"col-span-3 sm:col-span-1"},N={class:"col-span-3"},M={class:"grid grid-cols-1"},w={class:"col"},z={name:"FinanceTransactionForm"},G=Object.assign(z,{setup(F){const c=U(),s={type:"",primaryLedger:"",secondaryLedger:"",date:"",amount:"",description:"",media:[],mediaToken:"",mediaHash:""},_="finance/transaction/",u=V({types:[]}),t=q(_),n=V({...s}),p=V({primaryLedger:"",secondaryLedger:"",isLoaded:!c.params.uuid}),k=r=>{Object.assign(u,r)},B=r=>{var e,g,m,y,b,f;Object.assign(s,{type:r.type,primaryLedger:(e=r.ledger)==null?void 0:e.uuid,secondaryLedger:(m=(g=r.record)==null?void 0:g.ledger)==null?void 0:m.uuid,date:r.date,amount:r.amount,description:r.description,media:r.media,mediaToken:r.mediaToken}),Object.assign(n,H(s)),p.primaryLedger=(y=r.ledger)==null?void 0:y.uuid,p.secondaryLedger=(f=(b=r.record)==null?void 0:b.ledger)==null?void 0:f.uuid,p.isLoaded=!0};return(r,e)=>{const g=l("BaseSelect"),m=l("BaseSelectSearch"),y=l("DatePicker"),b=l("BaseInput"),f=l("BaseTextarea"),P=l("MediaUpload"),S=l("FormAction");return L(),v(S,{"pre-requisites":!0,onSetPreRequisites:k,"init-url":_,"init-form":s,form:n,"set-form":B,redirect:"FinanceTransaction"},{default:T(()=>[i("div",O,[i("div",D,[d(g,{disabled:!!o(c).params.uuid,modelValue:n.type,"onUpdate:modelValue":e[0]||(e[0]=a=>n.type=a),name:"type",label:r.$trans("finance.transaction.props.type"),options:u.types,error:o(t).type,"onUpdate:error":e[1]||(e[1]=a=>o(t).type=a)},null,8,["disabled","modelValue","label","options","error"])]),i("div",R,[p.isLoaded?(L(),v(m,{key:0,name:"primaryLedger",label:r.$trans("global.select",{attribute:r.$trans("finance.ledger.ledger")}),modelValue:n.primaryLedger,"onUpdate:modelValue":e[2]||(e[2]=a=>n.primaryLedger=a),error:o(t).primaryLedger,"onUpdate:error":e[3]||(e[3]=a=>o(t).primaryLedger=a),"label-prop":"name","value-prop":"uuid","init-search":p.primaryLedger,"search-action":"finance/ledger/list","additional-search-query":{subType:"primary"}},null,8,["label","modelValue","error","init-search"])):$("",!0)])]),i("div",h,[i("div",C,[p.isLoaded?(L(),v(m,{key:0,name:"secondaryLedger",label:r.$trans("global.select",{attribute:r.$trans("finance.ledger.secondary_ledger")}),modelValue:n.secondaryLedger,"onUpdate:modelValue":e[4]||(e[4]=a=>n.secondaryLedger=a),error:o(t).secondaryLedger,"onUpdate:error":e[5]||(e[5]=a=>o(t).secondaryLedger=a),"label-prop":"name","value-prop":"uuid","init-search":p.secondaryLedger,"search-action":"finance/ledger/list","additional-search-query":{subType:n.type=="contra"?"primary":"secondary"}},null,8,["label","modelValue","error","init-search","additional-search-query"])):$("",!0)]),i("div",E,[d(y,{modelValue:n.date,"onUpdate:modelValue":e[6]||(e[6]=a=>n.date=a),name:"date",label:r.$trans("finance.transaction.props.date"),"no-clear":"",error:o(t).date,"onUpdate:error":e[7]||(e[7]=a=>o(t).date=a)},null,8,["modelValue","label","error"])]),i("div",I,[d(b,{currency:"",type:"text",modelValue:n.amount,"onUpdate:modelValue":e[8]||(e[8]=a=>n.amount=a),name:"amount",label:r.$trans("finance.transaction.props.amount"),error:o(t).amount,"onUpdate:error":e[9]||(e[9]=a=>o(t).amount=a)},null,8,["modelValue","label","error"])]),i("div",N,[d(f,{modelValue:n.description,"onUpdate:modelValue":e[10]||(e[10]=a=>n.description=a),name:"description",label:r.$trans("finance.ledger.props.description"),error:o(t).description,"onUpdate:error":e[11]||(e[11]=a=>o(t).description=a)},null,8,["modelValue","label","error"])])]),i("div",M,[i("div",w,[d(P,{multiple:"",label:r.$trans("general.file"),module:"transaction",media:n.media,onSetHash:e[12]||(e[12]=a=>n.mediaHash=a),onSetToken:e[13]||(e[13]=a=>n.mediaToken=a)},null,8,["label","media"])])])]),_:1},8,["form"])}}}),J={name:"FinanceTransactionAction"},Q=Object.assign(J,{setup(F){const c=U();return(s,_)=>{const u=l("PageHeaderAction"),t=l("PageHeader"),n=l("ParentTransition");return L(),j(A,null,[d(t,{title:s.$trans(o(c).meta.trans,{attribute:s.$trans(o(c).meta.label)}),navs:[{label:s.$trans("finance.finance"),path:"Finance"},{label:s.$trans("finance.transaction.transaction"),path:"FinanceTransactionList"}]},{default:T(()=>[d(u,{name:"FinanceTransaction",title:s.$trans("finance.transaction.transaction"),actions:["list"]},null,8,["title"])]),_:1},8,["title","navs"]),d(n,{appear:"",visibility:!0},{default:T(()=>[d(G)]),_:1})],64)}}});export{Q as default};