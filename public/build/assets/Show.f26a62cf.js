import{u as w,k as F,m as S,s as C,r as s,o as p,c as u,d as t,j as a,e as i,F as H,h as P,v as o,x as r,a as m,i as V}from"./app.ca5d1c04.js";const A={class:"grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2"},D={key:0},I={key:1},N=["innerHTML"],j={name:"FinanceLedgerTypeShow"},M=Object.assign(j,{setup(R){w();const c=F(),_=S(),g={},f="finance/ledgerType/",n=C({...g}),y=e=>{Object.assign(n,e)};return(e,d)=>{const b=s("PageHeaderAction"),$=s("PageHeader"),l=s("BaseDataView"),B=s("BaseButton"),T=s("ShowButton"),h=s("BaseCard"),v=s("ShowItem"),L=s("ParentTransition");return p(),u(H,null,[t($,{title:e.$trans(i(c).meta.trans,{attribute:e.$trans(i(c).meta.label)}),navs:[{label:e.$trans("finance.finance"),path:"Finance"},{label:e.$trans("finance.ledger_type.ledger_type"),path:"FinanceLedgerTypeList"}]},{default:a(()=>[t(b,{name:"FinanceLedgerType",title:e.$trans("finance.ledger_type.ledger_type"),actions:["list"]},null,8,["title"])]),_:1},8,["title","navs"]),t(L,{appear:"",visibility:!0},{default:a(()=>[t(v,{"init-url":f,uuid:i(c).params.uuid,onSetItem:y,onRedirectTo:d[1]||(d[1]=k=>i(_).push({name:"FinanceLedgerType"}))},{default:a(()=>[n.uuid?(p(),P(h,{key:0},{name:a(()=>[o(r(n.name),1)]),footer:a(()=>[t(T,null,{default:a(()=>[t(B,{design:"primary",onClick:d[0]||(d[0]=k=>i(_).push({name:"FinanceLedgerTypeEdit",params:{uuid:n.uuid}}))},{default:a(()=>[o(r(e.$trans("general.edit")),1)]),_:1})]),_:1})]),default:a(()=>[m("dl",A,[t(l,{label:e.$trans("finance.ledger_type.props.name")},{default:a(()=>[o(r(n.name),1)]),_:1},8,["label"]),t(l,{label:e.$trans("finance.ledger_type.props.code")},{default:a(()=>[o(r(n.typeDisplay),1)]),_:1},8,["label"]),t(l,{label:e.$trans("finance.ledger_type.props.alias")},{default:a(()=>[o(r(n.alias),1)]),_:1},8,["label"]),t(l,{label:e.$trans("finance.ledger_type.props.parent")},{default:a(()=>[n.parent?(p(),u("span",D,r(n.parent.name),1)):(p(),u("span",I,"-"))]),_:1},8,["label"]),t(l,{class:"col-span-1 sm:col-span-2",label:e.$trans("finance.ledger_type.props.description")},{default:a(()=>[m("div",{innerHTML:n.description},null,8,N)]),_:1},8,["label"]),t(l,{label:e.$trans("general.created_at")},{default:a(()=>[o(r(n.createdAt),1)]),_:1},8,["label"]),t(l,{label:e.$trans("general.updated_at")},{default:a(()=>[o(r(n.updatedAt),1)]),_:1},8,["label"])])]),_:1})):V("",!0)]),_:1},8,["uuid"])]),_:1})],64)}}});export{M as default};
